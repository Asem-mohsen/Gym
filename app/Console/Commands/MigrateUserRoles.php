<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MigrateUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:migrate-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing users from old role system to new Spatie permission system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting user role migration...');

        // Get all users
        $users = User::all();
        $migratedCount = 0;

        foreach ($users as $user) {
            try {
                // Determine role based on is_admin flag and old role_id
                if ($user->is_admin) {
                    $roleName = 'admin';
                } else {
                    // Default to regular_user for non-admin users
                    $roleName = 'regular_user';
                }

                // Find the role
                $role = Role::where('name', $roleName)->first();
                
                if ($role) {
                    // Assign role to user
                    $user->assignRole($role);
                    $migratedCount++;
                    $this->line("Migrated user: {$user->name} -> {$roleName}");
                } else {
                    $this->warn("Role '{$roleName}' not found for user: {$user->name}");
                }
            } catch (\Exception $e) {
                $this->error("Error migrating user {$user->name}: " . $e->getMessage());
            }
        }

        $this->info("Migration completed! {$migratedCount} users migrated.");
        
        return Command::SUCCESS;
    }
}
