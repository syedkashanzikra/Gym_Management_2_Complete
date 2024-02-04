<?php

namespace App\Http\Controllers\Admin;

use App\Models\WeekTemplate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Coderstm\Traits\Helpers;
use Carbon\Carbon;

class WeekTemplateController extends Controller
{
    use Helpers;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(WeekTemplate::class);
    }

    /**
     * Display a listing of the week.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, WeekTemplate $weekTemplate)
    {
        if ($request->filled('month') && $request->filled('year')) {
            $month = $request->month + 1;
            $date = "{$request->year}-{$month}";
            $startOfMonth = Carbon::parse($date)->startOfMonth();
            $endOfMonth = Carbon::parse($date)->endOfMonth();
        } else {
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();
        }

        // get dates
        $dates = $this->weeksBetweenTwoDates($startOfMonth->startOfWeek(), $endOfMonth->endOfWeek()->addWeek());

        $weekTemplates = $weekTemplate->whereIn('start_of_week', $dates)->get()->map(function ($item) {
            return $item->toArray();
        });

        $data = collect($dates)->map(function ($item) use ($weekTemplates) {
            return $weekTemplates->firstWhere('start_of_week', $item) ?? [
                'start_of_week' => $item,
                'start_of_week_formated' => Carbon::parse($item)->format('d/m/Y'),
                'template' => null
            ];
        });

        return response()->json($data, 200);
    }

    /**
     * Updated class schedules of the week.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'weeks' => 'required|array',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        WeekTemplate::assignClassSchedule($request->weeks);

        return response()->json([
            'message' => trans('messages.update_class_schedule_weeks'),
        ], 200);
    }
}
