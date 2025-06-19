<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UpdateRolesToEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-roles-to-enum';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all existing roles to match the UserRole enum';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating roles to match UserRole enum...');

        // Get all enum values
        $roleValues = UserRole::values();

        // Create roles from enum if they don't exist
        foreach ($roleValues as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
            $this->info("Role '{$roleName}' ensured");
        }

        // Check for any roles that don't match the enum
        $dbRoles = Role::all();
        foreach ($dbRoles as $role) {
            if (!in_array($role->name, $roleValues)) {
                $this->warn("Found role '{$role->name}' that doesn't match any enum value. You may want to handle this manually.");
            }
        }

        $this->info('Roles updated successfully!');

        return Command::SUCCESS;
    }
}
