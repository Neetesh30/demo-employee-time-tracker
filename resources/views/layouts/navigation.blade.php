<div class="collapse navbar-collapse">

    <ul class="navbar-nav me-auto">

        <li class="nav-item">
            <a class="nav-link" href="{{ route('time-logs.index') }}">
                Time Logs
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('leaves.index') }}">
                Apply Leave
            </a>
        </li>

    </ul>

    <span class="text-white me-3">
        {{ auth()->user()->name }}
    </span>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button class="btn btn-light btn-sm">
            Logout
        </button>
    </form>

</div>