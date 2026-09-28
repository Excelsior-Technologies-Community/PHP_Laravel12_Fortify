<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teams & Multi-Tenancy Workspaces</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme_preference') || '{{ auth()->user()->theme_preference ?? "system" }}';
            if (savedTheme === 'dark' || (savedTheme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark-mode');
                document.addEventListener('DOMContentLoaded', () => document.body.classList.add('dark-mode'));
            }
        })();
    </script>

    <style>
        * { box-sizing: border-box; font-family: Arial, sans-serif; }
        body { margin: 0; background: #f4f6f9; color: #333; }

        body.dark-mode { background: #0f172a !important; color: #f8fafc !important; }
        body.dark-mode .card { background: #1e293b !important; color: #f8fafc !important; box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important; }
        body.dark-mode input, body.dark-mode select, body.dark-mode table { background: #334155 !important; color: white !important; border-color: #475569 !important; }
        body.dark-mode .navbar { background: #020617 !important; }

        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 18px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }

        .container { max-width: 1000px; margin: 40px auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 14px; box-shadow: 0 8px 25px rgba(0,0,0,.08); margin-bottom: 30px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #f8fafc; font-weight: bold; }
        body.dark-mode th { background: #0f172a !important; color: #94a3b8; }

        label { display: block; margin-bottom: 7px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 7px; }
        button { background: #667eea; color: white; border: 0; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-switch { background: #10b981; }
        .btn-remove { background: #ef4444; }

        .team-badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; background: #667eea; color: white; }
        .team-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 25px; }
        .team-box { border: 2px solid #e2e8f0; padding: 20px; border-radius: 12px; position: relative; }
        .team-box.active { border-color: #10b981; background: rgba(16, 185, 129, 0.05); }

        .success { background: #d1e7dd; color: #0f5132; padding: 14px; border-radius: 7px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #842029; padding: 14px; border-radius: 7px; margin-bottom: 20px; }
        .back { display: inline-block; margin-top: 15px; color: #667eea; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

@if(session()->has('impersonator_id'))
    <div style="background: #ffc107; color: #000; padding: 10px 20px; text-align: center; font-weight: bold; position: sticky; top: 0; z-index: 9999; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
        <div>⚠️ Impersonating <u>{{ auth()->user()->name }}</u> ({{ auth()->user()->email }})</div>
        <form method="POST" action="{{ route('security.impersonate.stop') }}" style="margin:0;">
            @csrf
            <button type="submit" style="background: #212529; color: white; border: none; padding: 6px 14px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                ⬅️ Return to Admin Account
            </button>
        </form>
    </div>
@endif

<div class="navbar">
    <strong>Teams & Multi-Tenancy Workspaces</strong>
    <a href="{{ route('dashboard') }}">Dashboard</a>
</div>

<div class="container">

    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">⚠️ {{ session('error') }}</div>
    @endif

    <!-- Workspace Switcher Section -->
    <div class="card">
        <h2>🏢 Active Workspace & Teams</h2>
        <p style="color: #64748b;">Jetstream style multi-tenancy workspaces. Select your active team to switch workspace context.</p>

        <div class="team-grid">
            @foreach($teams as $t)
                <div class="team-box {{ $currentTeam && $currentTeam->id === $t->id ? 'active' : '' }}">
                    @if($currentTeam && $currentTeam->id === $t->id)
                        <span style="position: absolute; top: 15px; right: 15px; background: #10b981; color: white; font-size: 11px; padding: 3px 8px; border-radius: 10px; font-weight: bold;">ACTIVE WORKSPACE</span>
                    @endif
                    <h3 style="margin-top:0;">🏢 {{ $t->name }}</h3>
                    <p style="color: #64748b; font-size: 13px;">Owner: {{ $t->owner->name }} ({{ $t->owner->email }})</p>
                    <p style="color: #64748b; font-size: 13px;">Members: {{ $t->users->count() }} users</p>

                    @if(!$currentTeam || $currentTeam->id !== $t->id)
                        <form method="POST" action="{{ route('security.teams.switch', $t->id) }}" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn-switch" style="padding: 6px 14px; font-size: 13px;">
                                🔄 Switch to Workspace
                            </button>
                        </form>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create New Team Workspace -->
    <div class="card">
        <h2>➕ Create New Team Workspace</h2>
        <form method="POST" action="{{ route('security.teams.store') }}">
            @csrf
            <label>Team Workspace Name</label>
            <input type="text" name="name" placeholder="e.g. Acme Corporation, Marketing Team" required>
            <button type="submit">Create Workspace</button>
        </form>
    </div>

    <!-- Active Team Members & Invitations -->
    @if($currentTeam)
        <div class="card">
            <h2>👥 Team Members in "{{ $currentTeam->name }}"</h2>
            <p style="color: #64748b;">Invite members with team-level roles (Admin / Member).</p>

            <form method="POST" action="{{ route('security.teams.invite', $currentTeam->id) }}" style="display: flex; gap: 10px; margin-bottom: 25px;">
                @csrf
                <div style="flex: 2;">
                    <input type="email" name="email" placeholder="Member User Email Address" required style="margin:0;">
                </div>
                <div style="flex: 1;">
                    <select name="role" style="margin:0;">
                        <option value="member">Member</option>
                        <option value="admin">Team Admin</option>
                    </select>
                </div>
                <button type="submit" style="margin:0;">✉️ Add Member</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Email</th>
                        <th>Team Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($currentTeam->users as $member)
                        <tr>
                            <td>
                                <img src="{{ $member->avatar_url }}" style="width: 26px; height: 26px; border-radius: 50%; vertical-align: middle; margin-right: 6px;">
                                <b>{{ $member->name }}</b>
                            </td>
                            <td>{{ $member->email }}</td>
                            <td>
                                <span class="team-badge">{{ ucfirst($member->pivot->role ?? 'member') }}</span>
                            </td>
                            <td>
                                @if($currentTeam->user_id !== $member->id)
                                    <form method="POST" action="{{ route('security.teams.remove', [$currentTeam->id, $member->id]) }}" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="btn-remove" style="padding: 4px 10px; font-size: 12px;">Remove</button>
                                    </form>
                                @else
                                    <small style="color: #64748b;">(Team Owner)</small>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <a class="back" href="{{ route('dashboard') }}">← Back to Dashboard</a>

</div>
</body>
</html>
