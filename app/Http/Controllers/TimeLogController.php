<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Leave;

class TimeLogController extends Controller
{
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
