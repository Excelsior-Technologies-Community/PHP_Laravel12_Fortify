<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $teams = $user->allTeams();

        // Ensure current_team_id is set
        if (! $user->current_team_id && $teams->count() > 0) {
            $user->switchTeam($teams->first());
        }

        $currentTeam = $user->currentTeam;

        return view('security.teams', compact('teams', 'currentTeam'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $team = Team::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'personal_team' => false,
        ]);

        // Attach owner to pivot as owner
        $team->users()->attach($request->user()->id, ['role' => 'owner']);

        // Switch to newly created team
        $request->user()->switchTeam($team);

        return redirect()->back()->with('success', "Team '{$team->name}' created and set as active workspace.");
    }

    public function switchTeam(Request $request, Team $team)
    {
        if ($request->user()->switchTeam($team)) {
            return redirect()->back()->with('success', "Switched workspace to '{$team->name}'.");
        }

        return redirect()->back()->with('error', "You do not have access to that workspace.");
    }

    public function inviteMember(Request $request, Team $team)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:admin,member',
        ]);

        $invitedUser = User::where('email', $request->email)->firstOrFail();

        if ($team->hasUser($invitedUser)) {
            return redirect()->back()->with('error', "User is already a member of this team.");
        }

        $team->users()->attach($invitedUser->id, ['role' => $request->role]);

        // If invited user has no active team, set it
        if (! $invitedUser->current_team_id) {
            $invitedUser->switchTeam($team);
        }

        return redirect()->back()->with('success', "{$invitedUser->name} added to team '{$team->name}' as {$request->role}.");
    }

    public function removeMember(Request $request, Team $team, User $user)
    {
        if ($team->user_id === $user->id) {
            return redirect()->back()->with('error', "Cannot remove team owner.");
        }

        $team->users()->detach($user->id);

        return redirect()->back()->with('success', "Member removed from team '{$team->name}'.");
    }
}
