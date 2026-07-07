<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Time Log System') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-800">
        <div class="flex min-h-screen items-center justify-center px-4 py-10">
            <div class="w-full max-w-5xl rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="grid gap-8 p-8 md:grid-cols-[1.2fr_0.8fr] md:p-12">
                    <div>
                        <p class="mb-3 text-sm font-semibold uppercase tracking-wide text-indigo-600">Time Log System</p>
                        <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Track your day in a simple, focused way.</h1>
                        <p class="mt-4 text-lg text-slate-600">
                            Log work entries, keep daily hours under control, and submit leave requests from one place.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            @if (Route::has('login'))
                                @auth
                                    <a href="{{ url('/time-logs') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                        Go to dashboard
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700">
                                        Log in
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            @endif
                        </div>

                        <div class="mt-8 rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <h2 class="text-sm font-semibold text-slate-900">Test account</h2>
                            <p class="mt-2 text-sm text-slate-600">Use these details to explore the app quickly.</p>
                            <ul class="mt-3 space-y-1 text-sm text-slate-700">
                                <li><span class="font-medium">Email:</span> test@example.com</li>
                                <li><span class="font-medium">Password:</span> password</li>
                            </ul>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-900 p-6 text-slate-100">
                        <h2 class="text-lg font-semibold">What you can do</h2>
                        <ul class="mt-4 space-y-3 text-sm text-slate-300">
                            <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Log multiple tasks for a day</li>
                            <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Track up to 10 working hours per day</li>
                            <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Submit leave requests with clear validation</li>
                            <li class="flex gap-2"><span class="mt-1 h-2 w-2 rounded-full bg-indigo-400"></span>Review your entries from the dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>