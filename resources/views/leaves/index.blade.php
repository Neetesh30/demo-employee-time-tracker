@extends('layouts.app')

@section('title', 'Apply Leave')

@section('content')

<div class="card">

    <div class="card-header">
        <h4>Apply Leave</h4>
    </div>

    <div class="card-body">

        <form action="{{ route('leaves.store') }}" method="POST">

            @csrf

            <div class="row">

                <div class="col-md-6 mb-3">

                    <label class="form-label">Start Date</label>

                    <input
                        type="date"
                        name="start_date"
                        class="form-control @error('start_date') is-invalid @enderror"
                        value="{{ old('start_date') }}"
                        required>

                    @error('start_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="col-md-6 mb-3">

                    <label class="form-label">End Date</label>

                    <input
                        type="date"
                        name="end_date"
                        class="form-control @error('end_date') is-invalid @enderror"
                        value="{{ old('end_date') }}"
                        required>

                    @error('end_date')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

            </div>

            <div class="mb-3">

                <label class="form-label">Reason</label>

                <textarea
                    name="reason"
                    rows="4"
                    class="form-control">{{ old('reason') }}</textarea>

            </div>

            <button class="btn btn-success w-100">
                Apply Leave
            </button>

        </form>

    </div>

</div>

<hr>

<h4>Leave History</h4>

<table class="table table-bordered table-striped">

    <thead>

    <tr>

        <th>Start Date</th>
        <th>End Date</th>
        <th>Reason</th>
        <th>Action</th>

    </tr>

    </thead>

    <tbody>

    @forelse($leaves as $leave)

        <tr>

            <td>{{ $leave->start_date }}</td>

            <td>{{ $leave->end_date }}</td>

            <td>{{ $leave->reason }}</td>

            <td>

                <form action="{{ route('leaves.destroy',$leave) }}"
                      method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete this leave request?')">

                        Delete

                    </button>

                </form>

            </td>

        </tr>

    @empty

        <tr>

            <td colspan="4" class="text-center">
                No leave applications found.
            </td>

        </tr>

    @endforelse

    </tbody>

</table>

@endsection