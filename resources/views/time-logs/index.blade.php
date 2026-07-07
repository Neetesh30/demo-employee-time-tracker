@extends('layouts.app')

@section('title', 'Time Logs')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Daily Time Log</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('time-logs.store') }}" method="POST" id="time-log-form">

            @csrf

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label>Date</label>

                    <input
                        type="date"
                        name="work_date"
                        class="form-control"
                        max="{{ now()->toDateString() }}"
                        value="{{ old('work_date', $date) }}"
                        required>

                    @error('work_date')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="border rounded p-3 mb-3">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Task Entries</h6>
                    <button type="button" class="btn btn-outline-secondary btn-sm" id="add-task-row">
                        Add Task
                    </button>
                </div>

                <div id="task-entry-list"></div>

                @error('tasks')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="card bg-light mb-4">
                <div class="card-body">
                    <h6 class="card-title">Tasks to submit</h6>
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Task</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody id="task-preview-body">
                            <tr>
                                <td colspan="3" class="text-muted">No tasks added yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <button class="btn btn-primary w-100">
                Save Time Logs
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
<style>
    .task-description-cell {
        white-space: normal;
        word-break: break-word;
        max-width: 320px;
    }
</style>

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

            <td class="task-description-cell">{{ $log->task_description }}</td>

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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const taskEntryList = document.getElementById('task-entry-list');
    const previewBody = document.getElementById('task-preview-body');
    const addTaskButton = document.getElementById('add-task-row');
    const projectOptions = @json($projects->map(function ($project) {
        return ['id' => $project->id, 'name' => $project->name];
    })->values());

    let taskIndex = 0;

    function renderPreview() {
        const rows = Array.from(taskEntryList.querySelectorAll('.task-entry'));

        if (rows.length === 0) {
            previewBody.innerHTML = '<tr><td colspan="3" class="text-muted">No tasks added yet.</td></tr>';
            return;
        }

        previewBody.innerHTML = rows.map(function (row) {
            const projectSelect = row.querySelector('select');
            const projectName = projectSelect && projectSelect.selectedOptions[0]
                ? projectSelect.selectedOptions[0].text
                : 'Select Project';
            const description = row.querySelector('textarea').value.trim() || '—';
            const time = row.querySelector('input').value.trim() || '—';

            return '<tr><td>' + projectName + '</td><td>' + description + '</td><td>' + time + '</td></tr>';
        }).join('');
    }

    function addTaskRow() {
        const row = document.createElement('div');
        row.className = 'task-entry row g-3 align-items-end border rounded p-3 mb-3';
        row.innerHTML = [
            '<div class="col-md-4">',
            '<label class="form-label">Project</label>',
            '<select name="tasks[' + taskIndex + '][project_id]" class="form-select" required>',
            '<option value="">Select Project</option>',
            projectOptions.map(function (project) {
                return '<option value="' + project.id + '">' + project.name + '</option>';
            }).join(''),
            '</select>',
            '</div>',
            '<div class="col-md-5">',
            '<label class="form-label">Task Description</label>',
            '<textarea name="tasks[' + taskIndex + '][task_description]" rows="2" class="form-control task-description-input" maxlength="500" required></textarea>',
            '<small class="text-muted remaining-count">500 characters remaining</small>',
            '</div>',
            '<div class="col-md-2">',
            '<label class="form-label">Duration (HH:MM)</label>',
            '<input type="text" name="tasks[' + taskIndex + '][time]" placeholder="02:30" class="form-control" required>',
            '</div>',
            '<div class="col-md-1">',
            '<button type="button" class="btn btn-outline-danger btn-sm remove-task-row">Remove</button>',
            '</div>'
        ].join('');

        taskEntryList.appendChild(row);
        taskIndex += 1;
        renderPreview();
    }

    addTaskButton.addEventListener('click', addTaskRow);

    taskEntryList.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-task-row')) {
            event.target.closest('.task-entry').remove();
            renderPreview();
        }
    });

    function updateCharacterCounts() {
        const rows = taskEntryList.querySelectorAll('.task-entry');

        rows.forEach(function (row) {
            const textarea = row.querySelector('.task-description-input');
            const counter = row.querySelector('.remaining-count');

            if (textarea && counter) {
                const remaining = 500 - textarea.value.length;
                counter.textContent = remaining + ' character' + (remaining === 1 ? '' : 's') + ' remaining';
            }
        });
    }

    taskEntryList.addEventListener('input', function (event) {
        if (event.target.classList.contains('task-description-input')) {
            updateCharacterCounts();
        }
        renderPreview();
    });
    taskEntryList.addEventListener('change', function (event) {
        if (event.target.classList.contains('task-description-input')) {
            updateCharacterCounts();
        }
        renderPreview();
    });

    updateCharacterCounts();
    addTaskRow();
});
</script>

@endsection