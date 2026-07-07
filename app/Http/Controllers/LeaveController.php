<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreLeaveRequest;
use App\Models\Leave;
use App\Models\TimeLog;
use Carbon\Carbon;
use Throwable;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('leaves.index', compact('leaves'));
    }

    public function store(StoreLeaveRequest $request)
    {
        try {
            $workExists = TimeLog::where('user_id', auth()->id())
                ->whereBetween('work_date', [
                    $request->start_date,
                    $request->end_date
                ])
                ->exists();

            if ($workExists) {
                return back()
                    ->withErrors([
                        'start_date' => 'Work report already exists during the selected leave period.'
                    ])
                    ->withInput();
            }

            $overlappingLeave = Leave::where('user_id', auth()->id())
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                        ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                        ->orWhere(function ($q) use ($request) {
                            $q->where('start_date', '<=', $request->start_date)
                                ->where('end_date', '>=', $request->end_date);
                        });
                })
                ->exists();

            if ($overlappingLeave) {
                return back()
                    ->withErrors([
                        'start_date' => 'A leave request already exists for the selected dates.'
                    ])
                    ->withInput();
            }

            Leave::create([
                'user_id' => auth()->id(),
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'reason' => $request->reason,
            ]);

            return redirect()
                ->route('leaves.index')
                ->with('success', 'Leave applied successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withErrors([
                    'general' => 'Something went wrong while saving your leave request. Please try again.'
                ])
                ->withInput();
        }
    }

    public function destroy(Leave $leave)
    {
        try {
            abort_if($leave->user_id != auth()->id(),403);

            $leave->delete();

            return back()->with('success','Leave deleted successfully.');
        } catch (Throwable $e) {
            report($e);

            return back()->with('error','Unable to delete this leave request right now.');
        }
    }
}
