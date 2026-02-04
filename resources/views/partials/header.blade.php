<!-- Shared Header -->
<header class="header">
    <div class="left-section">
        <img class="logoheader logo-clickable" src="{{ asset('images/ccb_logo_notext.svg') }}" onclick="openSidebar()">
    </div>

    <div class="right-section">
        @if(Auth::check())
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button class="auth-btn" type="submit">Logout</button>
            </form>
        @else
            <a class="auth-link" href="{{ route('login') }}">Sign In</a>
        @endif
    </div>
</header>
