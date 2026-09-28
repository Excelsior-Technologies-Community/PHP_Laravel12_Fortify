<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Role & Permission Management (RBAC)</title>
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

        .container { max-width: 1150px; margin: 40px auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 14px; box-shadow: 0 8px 25px rgba(0,0,0,.08); margin-bottom: 30px; }

        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #f8fafc; font-weight: bold; }
        body.dark-mode th { background: #0f172a !important; color: #94a3b8; }

        label { display: block; margin-bottom: 7px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 7px; }
        button { background: #667eea; color: white; border: 0; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .btn-impersonate { background: #0ea5e9; }
        .badge { padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; display: inline-block; }
        .badge-super { background: #dc2626; color: white; }
        .badge-admin { background: #2563eb; color: white; }
        .badge-manager { background: #d97706; color: white; }
        .badge-user { background: #64748b; color: white; }

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
    <strong>Role, Permission (RBAC) & Impersonation Studio</strong>
    <a href="{{ route('dashboard') }}">Dashboard</a>
</div>

<div class="container">

    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="error">⚠️ {{ session('error') }}</div>
    @endif

    <!-- Roles & Permissions Matrix -->
    <div class="card">
        <h2>🛡️ System Roles & Fine-Grained Permissions</h2>
        <p style="color: #64748b;">Manage role permissions and assign access levels across the platform.</p>

        <table>
            <thead>
                <tr>
                    <th>Role ID</th>
                    <th>Role Name</th>
                    <th>Label</th>
                    <th>Assigned Users</th>
                    <th>Permissions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>#{{ $role->id }}</td>
                        <td>
                            <span class="badge {{ $role->name == 'super-admin' ? 'badge-super' : ($role->name == 'admin' ? 'badge-admin' : ($role->name == 'manager' ? 'badge-manager' : 'badge-user')) }}">
                                {{ strtoupper($role->name) }}
                            </span>
                        </td>
                        <td>{{ $role->label }}</td>
                        <td>{{ $role->users->count() }} Users</td>
                        <td>
                            @forelse($role->permissions as $p)
                                <span style="font-size: 11px; background: #e2e8f0; color: #334155; padding: 2px 6px; border-radius: 4px; margin-right: 4px;">{{ $p->name }}</span>
                            @empty
                                <small style="color: #94a3b8;">All Permissions (Super Admin)</small>
                            @endforelse
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Custom Role -->
    <div class="card">
        <h2>➕ Create Custom Role</h2>
        <form method="POST" action="{{ route('security.roles.store') }}">
            @csrf
            <div style="display: flex; gap: 15px;">
                <div style="flex:1;">
                    <label>Role Name (e.g. auditor)</label>
                    <input type="text" name="name" placeholder="auditor" required>
                </div>
                <div style="flex:1;">
                    <label>Role Label</label>
                    <input type="text" name="label" placeholder="System Auditor">
                </div>
            </div>

            <label>Assign Permissions</label>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 20px;">
                @foreach($permissions as $perm)
                    <div>
                        <input type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm_{{ $perm->id }}" style="width: auto; margin-right: 6px;">
                        <label for="perm_{{ $perm->id }}" style="display: inline; font-weight: normal;">{{ $perm->label ?? $perm->name }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit">Create Role</button>
        </form>
    </div>

    <!-- User Role Assignment & Login As User (Impersonation) -->
    <div class="card">
        <h2>🕵️ User Management & "Login As User" Impersonation</h2>
        <p style="color: #64748b;">Super Admins can switch role assignments or instantly impersonate users for support/debugging.</p>

        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Current Role</th>
                    <th>Assign Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>
                            <img src="{{ $u->avatar_url }}" style="width: 28px; height: 28px; border-radius: 50%; vertical-align: middle; margin-right: 6px;">
                            <b>{{ $u->name }}</b>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @foreach($u->roles as $r)
                                <span class="badge {{ $r->name == 'super-admin' ? 'badge-super' : ($r->name == 'admin' ? 'badge-admin' : ($r->name == 'manager' ? 'badge-manager' : 'badge-user')) }}">
                                    {{ strtoupper($r->name) }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            <form method="POST" action="{{ route('security.roles.user.update', $u->id) }}" style="display: flex; gap: 6px; margin: 0;">
                                @csrf
                                <select name="role_id" style="padding: 4px 8px; margin: 0; width: 140px;">
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id }}" {{ $u->hasRole($r->name) ? 'selected' : '' }}>{{ $r->label }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" style="padding: 4px 10px; font-size: 12px;">Save</button>
                            </form>
                        </td>
                        <td>
                            @if(auth()->user()->id !== $u->id)
                                <form method="POST" action="{{ route('security.impersonate', $u->id) }}" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-impersonate" style="padding: 6px 12px; font-size: 12px;">
                                        👤 Login As {{ $u->name }}
                                    </button>
                                </form>
                            @else
                                <small style="color: #64748b;">(Current Session)</small>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <a class="back" href="{{ route('dashboard') }}">← Back to Dashboard</a>

</div>
</body>
</html>
