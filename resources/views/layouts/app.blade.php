<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Time Log System')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">

        <a class="navbar-brand" href="#">
            Time Log System
        </a>

        <div class="ms-auto d-flex align-items-center">

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

    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    @yield('content')

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>