@extends('layouts.app')

@section('title', 'Time Logs')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Daily Time Log</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('timelogs.store') }}" method="POST">

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

                </div>

                <div class="col-md-3 mb-3">

                    <label>Time (HH:MM)</label>

                    <input
                        type="text"
                        name="time"
                        placeholder="02:30"
                        class="form-control"
                        value="{{ old('time') }}"
                        required>

                </div>

            </div>

            <div class="mb-3">

                <label>Task Description</label>

                <textarea
                    name="task_description"
                    rows="3"
                    class="form-control"
                    required>{{ old('task_description') }}</textarea>

            </div>

            <button class="btn btn-primary">
                Add Task
            </button>

        </form>

    </div>

</div>

<hr>

<h5>Time Logs</h5>

@php
    $hours = intdiv($totalMinutes, 60);
    $minutes = $totalMinutes % 60;
@endphp

<div class="alert alert-info">

    Total Logged Time:

    <strong>

        {{ sprintf('%02d:%02d', $hours, $minutes) }}

    </strong>

</div>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Project</th>

            <th>Task</th>

            <th>Time</th>

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