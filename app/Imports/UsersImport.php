<?php

namespace App\Imports;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class UsersImport implements ToModel, WithHeadingRow, SkipsOnError
{
    protected $siteSettingId;
    protected $importedUsers = [];
    protected $errors = [];

    public function __construct(int $siteSettingId)
    {
        $this->siteSettingId = $siteSettingId;
    }

    public function model($row)
    {
        try {
            Log::info('Processing row in UsersImport:', ['row' => $row, 'type' => gettype($row)]);
            
            // Convert row to array if it's a collection or object
            if (is_object($row)) {
                if (method_exists($row, 'toArray')) {
                    $row = $row->toArray();
                } else {
                    $row = (array) $row;
                }
            }
            
            // Ensure row is an array
            if (!is_array($row)) {
                Log::warning('Row is not an array:', ['row' => $row, 'type' => gettype($row)]);
                return null;
            }
            
            Log::info('Row after conversion:', $row);
            
            // Find the actual column keys (they have descriptions in parentheses)
            $nameKey = $this->findColumnKey($row, 'name') ?? 'name';
            $emailKey = $this->findColumnKey($row, 'email') ?? 'email';
            $phoneKey = $this->findColumnKey($row, 'phone') ?? 'phone';
            $addressKey = $this->findColumnKey($row, 'address') ?? 'address';
            $genderKey = $this->findColumnKey($row, 'gender') ?? 'gender';
            $roleKey = $this->findColumnKey($row, 'role') ?? 'role';
            $statusKey = $this->findColumnKey($row, 'status') ?? 'status';
            
            // Skip if this is a header row or empty row
            if (empty($row[$nameKey]) || $row[$nameKey] === 'name') {
                Log::info('Skipping header or empty row:', $row);
                return null;
            }
            
            // Validate that this is actually user data (should have email field)
            if (empty($row[$emailKey])) {
                Log::warning('Skipping non-user data row (no email):', $row);
                return null;
            }
            
            // Check if user already exists to prevent duplicates
            if (User::where('email', $row[$emailKey])->exists()) {
                Log::info('User already exists, skipping: ' . $row[$emailKey]);
                return null;
            }
            
            // Convert phone to string if it's a number
            $phone = $row[$phoneKey] ?? null;
            if ($phone !== null) {
                $phone = (string) $phone;
            }
            
            // Create the user (explicitly exclude ID to prevent auto-increment issues)
            $userData = [
                'name' => $row[$nameKey],
                'email' => $row[$emailKey],
                'phone' => $phone,
                'address' => $row[$addressKey] ?? null,
                'gender' => $row[$genderKey] ?? null,
                'password' => null,
                'status' => $row[$statusKey] ?? 1,
                'is_admin' => 0, // Default to regular user
                'email_verified_at' => now(),
            ];
            
            // Explicitly remove any ID field that might be present
            unset($userData['id']);
            
            // Don't include ID - let auto-increment handle it
            // This prevents duplicate key errors
            
            // User creation and insertion
            $user = User::create($userData);

            // Role assignment
            $this->assignRole($user, $row[$roleKey] ?? 'regular_user');

            // Gym association
            $user->gyms()->attach($this->siteSettingId);

            // Store imported user data for reporting
            $this->importedUsers[] = [
                'user' => $user,
                'role' => $row[$roleKey] ?? 'regular_user'
            ];

            Log::info('User import completed successfully: ' . $row[$emailKey]);
            return $user;

        } catch (Exception $e) {
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

    public function onError(Throwable $e)
    {
        Log::error('User import error: ' . $e->getMessage());
        
        $this->errors[] = $e->getMessage();
    }

    protected function assignRole(User $user, string $roleName)
    {
        Log::info("Assigning role '{$roleName}' to user {$user->email}");
        
        // First, let's see what roles exist in the database
        $existingRoles = Role::all()->pluck('name')->toArray();
        Log::info("Available roles in database: " . implode(', ', $existingRoles));
        
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
            'management' => 'management',
            'sales' => 'sales',
        ];

        $mappedRoleName = $roleMapping[strtolower($roleName)] ?? 'regular_user';
        Log::info("Mapped role '{$roleName}' to '{$mappedRoleName}'");
        
        $role = Role::where('name', $mappedRoleName)->first();
        
        if ($role) {
            $user->assignRole($role);
            Log::info("Successfully assigned role '{$mappedRoleName}' to user {$user->email}");
            
            if (!in_array($mappedRoleName, ['regular_user','user','member'])) {
                $user->update(['is_admin' => 1]);
                Log::info("Updated user {$user->email} to admin status");
            }
        } else {
            Log::warning("Role '{$mappedRoleName}' not found, assigning default role");
            $defaultRole = Role::where('name', 'regular_user')->first();
            if ($defaultRole) {
                $user->assignRole($defaultRole);
                Log::info("Assigned default role 'regular_user' to user {$user->email}");
            } else {
                Log::error("Default role 'regular_user' not found in database");
                // Try to create the role if it doesn't exist
                try {
                    $newRole = Role::create(['name' => 'regular_user', 'guard_name' => 'web']);
                    $user->assignRole($newRole);
                    Log::info("Created and assigned default role 'regular_user' to user {$user->email}");
                } catch (Exception $e) {
                    Log::error("Failed to create default role: " . $e->getMessage());
                }
            }
        }
    }

    public function getImportedUsers(): array
    {
        return $this->importedUsers;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Find column key by searching for a term within the key names
     */
    protected function findColumnKey($row, $searchTerm)
    {
        // Convert row to array if it's a Collection
        if (is_object($row) && method_exists($row, 'toArray')) {
            $row = $row->toArray();
        } elseif (is_object($row)) {
            $row = (array) $row;
        }
        
        $keys = array_keys($row);
        
        foreach ($keys as $key) {
            if (stripos($key, $searchTerm) !== false) {
                return $key;
            }
        }
        
        return null;
    }
}
