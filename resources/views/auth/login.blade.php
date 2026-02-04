<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Cal's Chicken Bowl</title>

    <link rel="stylesheet" href="{{ asset('styles/general.css') }}">
    <link rel="stylesheet" href="{{ asset('styles/header.css') }}">
    <style>
        .login-container { max-width: 420px; margin: 120px auto; padding: 24px; background:#fbfbfb; border-radius:8px; border:1px solid rgba(0,0,0,0.08); box-shadow:0 10px 30px rgba(0,0,0,0.12); transition: box-shadow 180ms ease, transform 180ms ease; } 
        .login-container:hover { box-shadow:0 14px 40px rgba(0,0,0,0.16); transform: translateY(-2px); }
        .login-container h1{margin-bottom:12px}
        .form-group{margin-bottom:12px}
        .form-control{width:100%;padding:10px;border:1px solid #ddd;border-radius:6px;font-size:16px}
        .submit-btn{background:#9F0000;color:#fff;border:none;padding:10px 14px;border-radius:6px;cursor:pointer;font-size:16px}
        .errors{color:#9F0000;margin-bottom:12px}
    </style>
</head>
<body>
    @include('partials.header')

    <main>
        <div class="login-container">
            <h1>Sign In</h1>

            @if($errors->any())
                <div class="errors">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" name="username" type="text" class="form-control" value="{{ old('username') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                </div>

                <div class="form-group">
                    <button type="submit" class="submit-btn">Sign In</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
