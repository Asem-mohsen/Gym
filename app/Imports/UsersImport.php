<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Role;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    protected $siteSettingId;
    protected $importedUsers = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function model(array $row)
    {
        try {
            // Skip if this is a header row or empty row
            if (empty($row['name']) || $row['name'] === 'name') {
                return null;
            }
            
            // Validate that this is actually user data (should have email field)
            if (empty($row['email'])) {
                Log::warning('Skipping non-user data row:', $row);
                return null;
            }
            
            // Check if user already exists to prevent duplicates
            if (User::where('email', $row['email'])->exists()) {
                Log::info('User already exists, skipping: ' . $row['email']);
                return null;
            }
            

            
            // Generate a random password if not provided
            $password = $row['password'] ?? $this->generateRandomPassword();
            
            // Convert phone to string if it's a number
            $phone = $row['phone'] ?? null;
            if ($phone !== null) {
                $phone = (string) $phone;
            }
            
            // Create the user
            $user = User::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'phone' => $phone,
                'address' => $row['address'] ?? null,
                'gender' => $row['gender'] ?? null,
                'password' => Hash::make($password),
                'status' => $row['status'] ?? 1,
                'is_admin' => 0, // Default to regular user
                'email_verified_at' => now(),
            ]);

            // Assign role based on the role column
            $this->assignRole($user, $row['role'] ?? 'regular_user');

            // Associate user with the gym
            $user->gyms()->attach($this->siteSettingId);

            // Store imported user data for reporting
            $this->importedUsers[] = [
                'user' => $user,
                'password' => $password,
                'role' => $row['role'] ?? 'regular_user'
            ];

            Log::info('Successfully imported user: ' . $user->email);
            return $user;

        } catch (\Exception $e) {
            Log::error('User import error: ' . $e->getMessage(), [
                'row' => $row,
                'site_setting_id' => $this->siteSettingId
            ]);
            
            // Don't add duplicate key errors to the errors array since they're expected
            if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                $this->errors[] = $e->getMessage();
            }
            
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female',
            'role' => 'nullable|string|max:50',
            'status' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Name is required for all users.',
            'email.required' => 'Email is required for all users.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email already exists in the system.',
            'gender.in' => 'Gender must be either male or female.',
        ];
    }

    public function onError(\Throwable $e)
    {
        Log::error('User import error: ' . $e->getMessage());
        
        $this->errors[] = $e->getMessage();
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    protected function assignRole(User $user, string $roleName)
    {
        // Map role names to actual role names in the system
        $roleMapping = [
            'regular_user' => 'regular_user',
            'user' => 'regular_user',
            'member' => 'regular_user',
            'admin' => 'admin',
            'administrator' => 'admin',
            'trainer' => 'trainer',
            'staff' => 'staff',
            'employee' => 'staff',
        ];

        $mappedRoleName = $roleMapping[strtolower($roleName)] ?? 'regular_user';
        
        // Find the role
        $role = Role::where('name', $mappedRoleName)->first();
        
        if ($role) {
            $user->assignRole($role);
            
            // If it's an admin role, update the is_admin flag
            if (in_array($mappedRoleName, ['admin', 'administrator'])) {
                $user->update(['is_admin' => 1]);
            }
        } else {
            // Assign default role if specified role doesn't exist
            $defaultRole = Role::where('name', 'regular_user')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
            }
        }
    }

    protected function generateRandomPassword(): string
    {
        return \Illuminate\Support\Str::random(8);
    }

    public function getImportedUsers(): array
    {
        return $this->importedUsers;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
