<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FortifyAdvancedSecurityFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_it_can_render_roles_and_permissions_page()
    {
        $superAdmin = User::where('email', 'superadmin@example.com')->first();

        $response = $this->actingAs($superAdmin)->get(route('security.roles'));

        $response->assertStatus(200);
        $response->assertSee('Role, Permission (RBAC)');
    }

    public function test_it_can_render_sessions_page()
    {
        $user = User::where('email', 'test@example.com')->first();

        $response = $this->actingAs($user)->get(route('security.sessions'));

        $response->assertStatus(200);
        $response->assertSee('Active Sessions');
    }

    public function test_it_can_create_custom_role()
    {
        $superAdmin = User::where('email', 'superadmin@example.com')->first();

        $response = $this->actingAs($superAdmin)->post(route('security.roles.store'), [
            'name' => 'compliance-officer',
            'label' => 'Compliance Officer',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('roles', ['name' => 'compliance-officer']);
    }

    public function test_it_can_update_user_role()
    {
        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $testUser = User::where('email', 'test@example.com')->first();
        $adminRole = Role::where('name', 'admin')->first();

        $response = $this->actingAs($superAdmin)->post(route('security.roles.user.update', $testUser->id), [
            'role_id' => $adminRole->id,
        ]);

        $response->assertRedirect();
        $this->assertTrue($testUser->fresh()->hasRole('admin'));
    }

    public function test_it_can_create_and_switch_teams_workspaces()
    {
        $user = User::where('email', 'test@example.com')->first();

        $response = $this->actingAs($user)->post(route('security.teams.store'), [
            'name' => 'Innovations Lab',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('teams', ['name' => 'Innovations Lab']);

        $newTeam = Team::where('name', 'Innovations Lab')->first();
        $this->assertEquals($newTeam->id, $user->fresh()->current_team_id);
    }

    public function test_it_can_invite_member_to_team()
    {
        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $testUser = User::where('email', 'test@example.com')->first();
        $team = Team::where('name', 'Global HQ Team')->first();

        $response = $this->actingAs($superAdmin)->post(route('security.teams.invite', $team->id), [
            'email' => $testUser->email,
            'role' => 'admin',
        ]);

        $response->assertRedirect();
        $this->assertTrue($team->users->contains('id', $testUser->id));
    }

    public function test_it_can_impersonate_user_and_stop_impersonation()
    {
        $superAdmin = User::where('email', 'superadmin@example.com')->first();
        $testUser = User::where('email', 'test@example.com')->first();

        // 1. Start impersonating testUser
        $response = $this->actingAs($superAdmin)->post(route('security.impersonate', $testUser->id));

        $response->assertRedirect(route('dashboard'));
        $this->assertEquals($testUser->id, auth()->id());
        $this->assertEquals($superAdmin->id, session('impersonator_id'));

        // 2. Stop impersonating
        $responseStop = $this->post(route('security.impersonate.stop'));

        $responseStop->assertRedirect(route('security.roles'));
        $this->assertEquals($superAdmin->id, auth()->id());
        $this->assertFalse(session()->has('impersonator_id'));
    }

    public function test_it_can_update_avatar_type_preference()
    {
        $user = User::where('email', 'test@example.com')->first();

        $response = $this->actingAs($user)->post(route('security.avatar.update'), [
            'avatar_type' => 'gravatar',
        ]);

        $response->assertRedirect();
        $this->assertEquals('gravatar', $user->fresh()->avatar_type);
        $this->assertStringContainsString('gravatar.com', $user->fresh()->avatar_url);
    }

    public function test_it_can_update_theme_preference()
    {
        $user = User::where('email', 'test@example.com')->first();

        $response = $this->actingAs($user)->post(route('security.theme.update'), [
            'theme' => 'dark',
        ]);

        $response->assertRedirect();
        $this->assertEquals('dark', $user->fresh()->theme_preference);
    }
}
