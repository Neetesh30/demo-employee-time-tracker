<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeLogRequest;
use App\Models\Leave;
use App\Models\Project;
use App\Models\TimeLog;
use Carbon\Carbon;

class TimeLogController extends Controller
{
    public function index()
    {
        $date = request('date', Carbon::today()->toDateString());

        $projects = Project::orderBy('name')->get();

        $timeLogs = TimeLog::with('project')
            ->where('user_id', auth()->id())
            ->whereDate('work_date', $date)
            ->get();

        $totalMinutes = $timeLogs->sum('total_minutes');

        return view('time-logs.index', compact(
            'projects',
            'timeLogs',
            'date',
            'totalMinutes'
        ));
    }

    public function store(StoreTimeLogRequest $request)
{
    [$hours, $minutes] = explode(':', $request->time);

    $hours = (int) $hours;
    $minutes = (int) $minutes;

    $totalMinutes = ($hours * 60) + $minutes;

    $leaveExists = Leave::where('user_id', auth()->id())
        ->whereDate('start_date', '<=', $request->work_date)
        ->whereDate('end_date', '>=', $request->work_date)
        ->exists();

    if ($leaveExists) {

        return back()
            ->withErrors([
                'work_date' => 'You have already applied for leave on this date.'
            ])
            ->withInput();
    }

    $loggedMinutes = TimeLog::where('user_id', auth()->id())
        ->whereDate('work_date', $request->work_date)
        ->sum('total_minutes');

    if (($loggedMinutes + $totalMinutes) > 600) {

        return back()
            ->withErrors([
                'time' => 'Maximum daily working hours is 10.'
            ])
            ->withInput();
    }

    TimeLog::create([
        'user_id' => auth()->id(),
        'work_date' => $request->work_date,
        'project_id' => $request->project_id,
        'task_description' => $request->task_description,
        'hours' => $hours,
        'minutes' => $minutes,
        'total_minutes' => $totalMinutes,
    ]);

    return redirect()
        ->route('time-logs.index', [
            'date' => $request->work_date
        ])
        ->with('success', 'Time log added successfully.');
}

    public function destroy(TimeLog $timeLog)
{
    if ($timeLog->user_id !== auth()->id()) {
        abort(403);
    }

    $timeLog->delete();

    return back()->with('success', 'Time log deleted successfully.');
}
}