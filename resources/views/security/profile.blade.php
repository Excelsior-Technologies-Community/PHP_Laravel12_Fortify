<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile & Avatar Studio</title>

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
        body { margin: 0; background: #f4f6f9; color: #333; transition: background 0.3s, color 0.3s; }

        body.dark-mode { background: #0f172a !important; color: #f8fafc !important; }
        body.dark-mode .card { background: #1e293b !important; color: #f8fafc !important; box-shadow: 0 10px 25px rgba(0,0,0,0.3) !important; }
        body.dark-mode input, body.dark-mode select { background: #334155 !important; color: white !important; border-color: #475569 !important; }
        body.dark-mode .navbar { background: #020617 !important; }

        .navbar { background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 18px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; font-weight: bold; }

        .container { max-width: 850px; margin: 40px auto; padding: 20px; }
        .card { background: white; padding: 35px; border-radius: 14px; box-shadow: 0 8px 25px rgba(0,0,0,.08); margin-bottom: 30px; }

        label { display: block; margin-bottom: 7px; font-weight: bold; }
        input, select { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 7px; }
        button { background: #667eea; color: white; border: 0; padding: 12px 20px; border-radius: 7px; cursor: pointer; font-weight: bold; }
        button:hover { opacity: 0.9; }

        .success { background: #d1e7dd; color: #0f5132; padding: 14px; border-radius: 7px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #842029; padding: 14px; border-radius: 7px; margin-bottom: 20px; }
        .back { display: inline-block; margin-top: 15px; color: #667eea; text-decoration: none; font-weight: bold; }

        .avatar-preview-container { display: flex; align-items: center; gap: 20px; margin-bottom: 20px; }
        .avatar-lg { width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .theme-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
        .theme-card { border: 2px solid #cbd5e1; padding: 15px; border-radius: 10px; text-align: center; cursor: pointer; font-weight: bold; transition: 0.2s; }
        .theme-card.active { border-color: #667eea; background: rgba(102, 126, 234, 0.1); }
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
    <strong>Profile, Avatar & Theme Studio</strong>
    <a href="{{ route('dashboard') }}">Dashboard</a>
</div>

<div class="container">

    @if(session('success'))
        <div class="success">✅ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <div>⚠️ {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <!-- Profile Information Card -->
    <div class="card">
        <h2>👤 Account Details</h2>
        <form method="POST" action="{{ route('security.profile.update') }}">
            @csrf
            <label>Full Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>

            <label>Email Address</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>

            <button type="submit">Update Profile Info</button>
        </form>
    </div>

    <!-- Avatar Upload & Gravatar Studio -->
    <div class="card">
        <h2>🖼️ Profile Avatar & Cropping Studio</h2>
        <p style="color: #64748b;">Upload a custom picture, use Gravatar auto-fetch, or UI initials.</p>

        <div class="avatar-preview-container">
            <img src="{{ $user->avatar_url }}" alt="Avatar" class="avatar-lg" id="avatarPreview">
            <div>
                <strong>Current Avatar Type:</strong> {{ ucfirst($user->avatar_type) }}<br>
                <small style="color: #64748b;">Gravatar Email: {{ $user->email }}</small>
            </div>
        </div>

        <form method="POST" action="{{ route('security.avatar.update') }}" enctype="multipart/form-data">
            @csrf
            <label>Select Avatar Type</label>
            <select name="avatar_type" id="avatarTypeSelect" onchange="toggleAvatarFileField()">
                <option value="initials" {{ $user->avatar_type === 'initials' ? 'selected' : '' }}>🎨 Generated UI Initials</option>
                <option value="gravatar" {{ $user->avatar_type === 'gravatar' ? 'selected' : '' }}>🌐 Gravatar Auto-Fetch (Gravatar.com)</option>
                <option value="custom" {{ $user->avatar_type === 'custom' ? 'selected' : '' }}>📁 Custom File Upload & Crop</option>
            </select>

            <div id="fileUploadGroup" style="{{ $user->avatar_type === 'custom' ? '' : 'display:none;' }}">
                <label>Upload Image File (JPG, PNG, WEBP max 2MB)</label>
                <input type="file" name="avatar_file" accept="image/*" onchange="previewImage(this)">
            </div>

            <button type="submit">Save Avatar Settings</button>
        </form>
    </div>

    <!-- Theme Customizer -->
    <div class="card">
        <h2>🎨 Theme Customizer (Dark / Light Mode)</h2>
        <p style="color: #64748b;">Choose your preferred color theme preference.</p>

        <form method="POST" action="{{ route('security.theme.update') }}" id="themeForm">
            @csrf
            <div class="theme-grid">
                <div class="theme-card {{ $user->theme_preference === 'light' ? 'active' : '' }}" onclick="selectTheme('light')">
                    ☀️ Light Mode
                </div>
                <div class="theme-card {{ $user->theme_preference === 'dark' ? 'active' : '' }}" onclick="selectTheme('dark')">
                    🌙 Dark Mode
                </div>
                <div class="theme-card {{ $user->theme_preference === 'system' ? 'active' : '' }}" onclick="selectTheme('system')">
                    🖥️ System Default
                </div>
            </div>
            <input type="hidden" name="theme" id="selectedThemeInput" value="{{ $user->theme_preference }}">
            <button type="submit">Save Theme Preference</button>
        </form>
    </div>

    <a class="back" href="{{ route('dashboard') }}">← Back to Dashboard</a>

</div>

<script>
    function toggleAvatarFileField() {
        const type = document.getElementById('avatarTypeSelect').value;
        document.getElementById('fileUploadGroup').style.display = (type === 'custom') ? 'block' : 'none';
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function selectTheme(theme) {
        document.getElementById('selectedThemeInput').value = theme;
        localStorage.setItem('theme_preference', theme);

        if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
            document.body.classList.add('dark-mode');
        } else {
            document.documentElement.classList.remove('dark-mode');
            document.body.classList.remove('dark-mode');
        }

        document.querySelectorAll('.theme-card').forEach(card => card.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }
</script>

</body>
</html>