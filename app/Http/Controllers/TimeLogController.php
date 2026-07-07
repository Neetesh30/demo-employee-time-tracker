<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTimeLogRequest;
use App\Models\Leave;
use App\Models\Project;
use App\Models\TimeLog;
use Carbon\Carbon;
use Throwable;

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
        try {
            $tasks = $request->filled('tasks') && is_array($request->input('tasks'))
                ? $request->input('tasks')
                : [[
                    'project_id' => $request->project_id,
                    'task_description' => $request->task_description,
                    'time' => $request->time,
                ]];

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

            $newMinutes = 0;
            $parsedTasks = [];

            foreach ($tasks as $task) {
                [$hours, $minutes] = $this->parseTime($task['time'] ?? '');
                $taskMinutes = ($hours * 60) + $minutes;

                if ($hours > 10 || ($hours === 10 && $minutes > 0) || $taskMinutes > 600) {
                    return back()
                        ->withErrors([
                            'tasks' => 'Each task must be 10 hours or less, and the daily total must not exceed 10 hours.'
                        ])
                        ->withInput();
                }

                $newMinutes += $taskMinutes;
                $parsedTasks[] = [
                    'project_id' => $task['project_id'],
                    'task_description' => $task['task_description'],
                    'hours' => $hours,
                    'minutes' => $minutes,
                    'total_minutes' => $taskMinutes,
                ];
            }

            if (($loggedMinutes + $newMinutes) > 600) {
                return back()
                    ->withErrors([
                        'tasks' => 'The total time for this date cannot exceed 10 hours.'
                    ])
                    ->withInput();
            }

            foreach ($parsedTasks as $task) {
                TimeLog::create([
                    'user_id' => auth()->id(),
                    'work_date' => $request->work_date,
                    'project_id' => $task['project_id'],
                    'task_description' => $task['task_description'],
                    'hours' => $task['hours'],
                    'minutes' => $task['minutes'],
                    'total_minutes' => $task['total_minutes'],
                ]);
            }

            return redirect()
                ->route('time-logs.index', [
                    'date' => $request->work_date
                ])
                ->with('success', 'Time log added successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors([
                    'general' => 'Something went wrong while saving your time log. Please try again.'
                ])
                ->withInput();
        }
    }

    private function parseTime(string $time): array
    {
        [$hours, $minutes] = array_pad(explode(':', $time), 2, 0);

        return [(int) $hours, (int) $minutes];
    }

    public function destroy(TimeLog $timeLog)
    {
        try {
            if ($timeLog->user_id !== auth()->id()) {
                abort(403);
            }

            $timeLog->delete();

            return back()->with('success', 'Time log deleted successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error', 'Unable to delete this time log right now.');
        }
    }
}