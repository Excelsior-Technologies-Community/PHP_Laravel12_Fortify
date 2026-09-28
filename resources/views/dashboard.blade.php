<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Fortify Security Dashboard</title>

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
        body { margin: 0; min-height: 100vh; background: linear-gradient(135deg, #667eea, #764ba2); color: #333; transition: background 0.3s; }

        body.dark-mode { background: #0f172a !important; color: #f8fafc !important; }
        body.dark-mode .welcome-card, body.dark-mode .security-card { background: #1e293b !important; color: #f8fafc !important; box-shadow: 0 10px 25px rgba(0,0,0,0.4) !important; }
        body.dark-mode .security-card p { color: #94a3b8 !important; }
        body.dark-mode .navbar { background: #020617 !important; }

        .navbar { background: rgba(255,255,255,.15); backdrop-filter: blur(10px); padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; color: white; }
        .nav-right { display: flex; align-items: center; gap: 15px; }

        .logout-btn { background: white; color: #667eea; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        .theme-btn { background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-weight: bold; }

        .container { max-width: 1150px; margin: 40px auto; padding: 20px; }

        .welcome-card { background: white; padding: 35px; text-align: center; border-radius: 14px; box-shadow: 0 20px 40px rgba(0,0,0,.2); margin-bottom: 30px; }
        .avatar-img { width: 85px; height: 85px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea; margin: 0 auto 15px; display: block; box-shadow: 0 6px 16px rgba(0,0,0,0.15); }

        .security-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .security-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,.15); }
        .security-card h2 { font-size: 19px; margin-top: 0; }
        .security-card p { color: #666; line-height: 1.5; min-height: 60px; }
        .security-card a { display: inline-block; background: #667eea; color: white; padding: 10px 15px; border-radius: 6px; text-decoration: none; font-weight: bold; }
        .danger a { background: #dc3545; }

        .role-badge { background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .workspace-badge { background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: bold; }

        @media(max-width:800px) { .security-grid { grid-template-columns: 1fr; } }
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
    <h3>Fortify Security Dashboard</h3>

    <div class="nav-right">
        @if(auth()->user()->currentTeam)
            <a href="{{ route('security.teams') }}" style="color:white; text-decoration:none;" class="workspace-badge">
                🏢 Workspace: {{ auth()->user()->currentTeam->name }}
            </a>
        @endif

        <button class="theme-btn" onclick="toggleDarkMode()">
            ☀️ / 🌙 Toggle Theme
        </button>

        <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>
    </div>
</div>

<div class="container">

    <div class="welcome-card">
        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="avatar-img">
        <h1 style="margin: 5px 0 10px;">Welcome, {{ auth()->user()->name }}</h1>
        <p style="margin-bottom: 15px;">{{ auth()->user()->email }}</p>

        <div style="display: flex; justify-content: center; gap: 10px; margin-bottom: 15px;">
            <span class="role-badge">
                🛡️ Role: {{ auth()->user()->roles->pluck('label')->first() ?? 'User' }}
            </span>
            <span class="workspace-badge">
                🏢 Active Workspace: {{ auth()->user()->currentTeam->name ?? 'Personal Workspace' }}
            </span>
        </div>

        <strong>Fortify Authentication & Security Suite</strong>
    </div>

    <div class="security-grid">

        <div class="security-card">
            <h2>🛡️ Roles & Permissions (RBAC)</h2>
            <p>Manage system roles, permissions, user role assignments, and "Login As User" impersonation.</p>
            <a href="{{ route('security.roles') }}">Manage RBAC</a>
        </div>

        <div class="security-card">
            <h2>🏢 Teams & Workspaces</h2>
            <p>Jetstream style multi-tenancy workspaces, team member invites, and workspace switching.</p>
            <a href="{{ route('security.teams') }}">Manage Teams</a>
        </div>

        <div class="security-card">
            <h2>🖼️ Profile & Avatar Studio</h2>
            <p>Update name, email, custom profile picture upload with cropping preview, or Gravatar auto-fetch.</p>
            <a href="{{ route('security.profile') }}">Profile & Avatar</a>
        </div>

        <div class="security-card">
            <h2>📊 Security Overview</h2>
            <p>View your complete authentication security statistics and recent activity.</p>
            <a href="{{ route('security.overview') }}">Open Overview</a>
        </div>

        <div class="security-card">
            <h2>🔐 Change Password</h2>
            <p>Change your password with current-password verification and strength checking.</p>
            <a href="{{ route('security.password') }}">Change Password</a>
        </div>

        <div class="security-card">
            <h2>🛡️ Two-Factor Authentication</h2>
            <p>Manage 2FA, QR setup and recovery codes.</p>
            <a href="{{ route('security.two-factor') }}">Manage 2FA</a>
        </div>

        <div class="security-card">
            <h2>📋 Login History</h2>
            <p>Search, filter and export login, failed and logout activity.</p>
            <a href="{{ route('security.login-history') }}">View History</a>
        </div>

        <div class="security-card">
            <h2>💻 Active Sessions</h2>
            <p>Search active devices and revoke unwanted sessions.</p>
            <a href="{{ route('security.sessions') }}">Manage Sessions</a>
        </div>

        <div class="security-card danger">
            <h2>🗑️ Delete Account</h2>
            <p>Permanently delete your account after confirming your password.</p>
            <a href="{{ route('security.delete-account') }}">Delete Account</a>
        </div>

    </div>

</div>

<script>
    function toggleDarkMode() {
        const isDark = document.body.classList.toggle('dark-mode');
        document.documentElement.classList.toggle('dark-mode', isDark);
        const newTheme = isDark ? 'dark' : 'light';
        localStorage.setItem('theme_preference', newTheme);

        fetch('{{ route("security.theme.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ theme: newTheme })
        });
    }
</script>

</body>
</html>