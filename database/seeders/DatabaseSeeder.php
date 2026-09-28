<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Permissions
        $p1 = Permission::firstOrCreate(['name' => 'manage-roles'], ['label' => 'Manage Roles & RBAC']);
        $p2 = Permission::firstOrCreate(['name' => 'manage-teams'], ['label' => 'Manage Teams & Workspaces']);
        $p3 = Permission::firstOrCreate(['name' => 'impersonate-users'], ['label' => 'Impersonate Users']);
        $p4 = Permission::firstOrCreate(['name' => 'view-security-logs'], ['label' => 'View Security & Audit Logs']);

        // 2. Roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin'], ['label' => 'Super Administrator']);
        $adminRole = Role::firstOrCreate(['name' => 'admin'], ['label' => 'Administrator']);
        $managerRole = Role::firstOrCreate(['name' => 'manager'], ['label' => 'Store Manager']);
        $userRole = Role::firstOrCreate(['name' => 'user'], ['label' => 'Regular User']);

        $superAdminRole->permissions()->sync([$p1->id, $p2->id, $p3->id, $p4->id]);
        $adminRole->permissions()->sync([$p2->id, $p4->id]);

        // 3. Super Admin User
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'avatar_type' => 'initials',
                'theme_preference' => 'system',
            ]
        );
        $superAdmin->roles()->sync([$superAdminRole->id]);

        // 4. Regular / Manager Users
        $managerUser = User::firstOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Manager Alex',
                'password' => Hash::make('password'),
                'avatar_type' => 'gravatar',
                'theme_preference' => 'dark',
            ]
        );
        $managerUser->roles()->sync([$managerRole->id]);

        $testUser = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password'),
                'avatar_type' => 'initials',
                'theme_preference' => 'light',
            ]
        );
        $testUser->roles()->sync([$userRole->id]);

        // 5. Teams & Workspaces
        $adminTeam = Team::firstOrCreate(
            ['name' => 'Global HQ Team'],
            ['user_id' => $superAdmin->id, 'personal_team' => true]
        );
        $adminTeam->users()->syncWithoutDetaching([
            $superAdmin->id => ['role' => 'owner'],
            $managerUser->id => ['role' => 'admin'],
            $testUser->id => ['role' => 'member'],
        ]);
        $superAdmin->switchTeam($adminTeam);

        $devTeam = Team::firstOrCreate(
            ['name' => 'Engineering Workspace'],
            ['user_id' => $managerUser->id, 'personal_team' => false]
        );
        $devTeam->users()->syncWithoutDetaching([
            $managerUser->id => ['role' => 'owner'],
            $testUser->id => ['role' => 'member'],
        ]);
        $managerUser->switchTeam($devTeam);

        if (! $testUser->current_team_id) {
            $testUser->switchTeam($adminTeam);
        }
    }
}
