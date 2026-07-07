@extends('layouts.app')

@section('title', 'Time Logs')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Daily Time Log</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('time-logs.store') }}" method="POST">

            @csrf

            <div class="row">

                <div class="col-md-3 mb-3">

                    <label>Date</label>

                    <input
                        type="date"
                        name="work_date"
                        class="form-control"
                        max="{{ now()->toDateString() }}"
                        value="{{ old('work_date', $date) }}"
                        required>

                        @error('work_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="col-md-3 mb-3">

                    <label>Project</label>

                    <select
                        name="project_id"
                        class="form-select"
                        required>

                        <option value="">Select Project</option>

                        @foreach($projects as $project)

                            <option
                                value="{{ $project->id }}"
                                @selected(old('project_id') == $project->id)>

                                {{ $project->name }}

                            </option>

                        @endforeach

                    </select>

                    @error('project_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="col-md-3 mb-3">

                    <label>Duration  (HH:MM)</label>

                    <input
                        type="text"
                        name="time"
                        placeholder="02:30"
                        class="form-control"
                        value="{{ old('time') }}"
                        required>

                        <small class="text-muted">
                            Enter the time spent on this task (e.g. 02:30 = 2 hours 30 minutes).
                        </small>

                </div>

                @error('time')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="mb-3">

                <label>Task Description</label>

                <textarea
                    name="task_description"
                    rows="3"
                    class="form-control"
                    required>{{ old('task_description') }}</textarea>

                    @error('task_description')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

            </div>

            <button class="btn btn-primary w-100">
                Add Task
            </button>

        </form>

    </div>

</div>

<hr>

<h5>Time Logs</h5>

@php
$totalHours = intdiv($totalMinutes, 60);
$totalMins = $totalMinutes % 60;

$remainingMinutes = max(0, 600 - $totalMinutes);

$remainingHours = intdiv($remainingMinutes, 60);
$remainingMins = $remainingMinutes % 60;
@endphp

<div class="row mb-4">

    <div class="col-md-6">

        <div class="alert alert-success">

            <strong>Total Logged:</strong>

            {{ sprintf('%02d:%02d', $totalHours, $totalMins) }}

        </div>

    </div>

    <div class="col-md-6">

        <div class="alert alert-warning">

            <strong>Remaining:</strong>

            {{ sprintf('%02d:%02d', $remainingHours, $remainingMins) }}

        </div>

    </div>

</div>

<table class="table table-bordered table-striped table-hover align-middle">

    <thead>

        <tr>

            <th>Project</th>

            <th>Task</th>

            <th>Time</th>

            <th>Action</th>

        </tr>

    </thead>

    <tbody>

    @forelse($timeLogs as $log)

        <tr>

            <td>{{ $log->project->name }}</td>

            <td>{{ $log->task_description }}</td>

            <td>
                {{ sprintf('%02d:%02d', $log->hours, $log->minutes) }}
            </td>

            <td>

            <form action="{{ route('time-logs.destroy',$log) }}"
                method="POST">

                @csrf
                @method('DELETE')

                <button
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Delete this time log?')">

                    Delete

                </button>

            </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="3" class="text-center">
                No entries found.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>