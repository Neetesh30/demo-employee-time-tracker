<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Leave;


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

    public function store(Request $request)
    {
        $date = $request->work_date;

        // Check if user is on leave
        $leaveExists = Leave::where('user_id', auth()->id())
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->exists();

        if ($leaveExists) {
            return back()->withErrors([
                'work_date' => 'You have already applied for leave on this date.'
            ]);
        }

        // Continue with time validation and save...
    }
}
