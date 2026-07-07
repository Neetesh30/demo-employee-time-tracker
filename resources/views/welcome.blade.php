<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Time Log System') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-10">

                <div class="card shadow-lg border-0">

                    <div class="card-body p-5">

                        <div class="row align-items-center">

                            <!-- Left Section -->

                            <div class="col-lg-7">

                                <span class="badge bg-primary mb-3">
                                    Time Log System
                                </span>

                                <h1 class="display-5 fw-bold">
                                    Track your work with ease.
                                </h1>

                                <p class="lead text-muted mt-3">
                                    Log daily work, monitor your working hours,
                                    and manage leave requests from one place.
                                </p>

                                <div class="mt-4">

                                    @if (Route::has('login'))

                                        @auth

                                            <a href="{{ route('time-logs.index') }}"
                                               class="btn btn-primary btn-lg me-2">
                                                Go to Time Logs
                                            </a>

                                        @else

                                            <a href="{{ route('login') }}"
                                               class="btn btn-primary btn-lg me-2">
                                                Login
                                            </a>

                                            @if (Route::has('register'))

                                                <a href="{{ route('register') }}"
                                                   class="btn btn-outline-primary btn-lg">
                                                    Register
                                                </a>

                                            @endif

                                        @endauth

                                    @endif

                                </div>

                                <div class="card mt-5">

                                    <div class="card-header">
                                        Test Account
                                    </div>

                                    <div class="card-body">

                                        <p class="mb-2">
                                            <strong>Email:</strong>
                                            test@example.com
                                        </p>

                                        <p class="mb-0">
                                            <strong>Password:</strong>
                                            password
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <!-- Right Section -->

                            <div class="col-lg-5 mt-5 mt-lg-0">

                                <div class="card bg-dark text-white">

                                    <div class="card-header">
                                        Features
                                    </div>

                                    <div class="card-body">

                                        <ul class="list-group list-group-flush">

                                            <li class="list-group-item bg-dark text-white">
                                                log multiple tasks per day
                                            </li>

                                            <li class="list-group-item bg-dark text-white">
                                                Maximum 10 working hours/day
                                            </li>

                                            <li class="list-group-item bg-dark text-white">
                                                Apply leave with validation
                                            </li>

                                            <li class="list-group-item bg-dark text-white">
                                                User-specific work reports
                                            </li>

                                            <li class="list-group-item bg-dark text-white">
                                                Daily work summary
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="text-center mt-4 text-muted">
                    © {{ date('Y') }} Time Log System
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>