<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\ClassSchedule;
use App\Http\Controllers\Controller;
use Coderstm\Traits\Helpers;
use Barryvdh\DomPDF\Facade\Pdf;

class WeekScheduleController extends Controller
{
    use Helpers;

    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(ClassSchedule::class);
    }

    private function query(Request $request, ClassSchedule $classSchedule)
    {
        $startOfWeek = $request->filled('startOfWeek') ? Carbon::parse($request->startOfWeek)->startOfWeek() : now()->startOfWeek();
        $sortBy = $request->otherSortBy != 'start_at' ? $request->otherSortBy : $request->sortBy;
        $otherSortBy = $request->otherSortBy != 'start_at' ? $request->sortBy : $request->otherSortBy;

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = Carbon::parse($request->input('date_from'));
            $to = Carbon::parse($request->input('date_to'));
            $date_from = $from->format('Y-m-d');
            $date_to = $to->format('Y-m-d');
            $classSchedule = $classSchedule->whereRaw('date_at BETWEEN ? AND ?',  [$date_from, $date_to]);
            $date = $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y');
        } else if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $classSchedule = $classSchedule->where([
                'day' => $date->dayName,
                'start_of_week' => $date->startOfWeek()
            ]);
            $date = $date->format('d/m/Y');
        } else {
            $classSchedule = $classSchedule->where('start_of_week', $startOfWeek);
            $date = $startOfWeek->format('d/m/Y');
        }

        if ($request->filled('filter')) {
            $classSchedule->whereHas('class', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->filter}%");
            });
        }

        if ($request->filled('class')) {
            $classSchedule->where('class_schedules.class_id', $request->class);
        }

        if ($request->filled('location')) {
            $classSchedule->where('location_id', $request->location);
        }

        if ($request->filled('instructor')) {
            $classSchedule->where('class_schedules.instructor_id', $request->instructor);
        }

        if ($request->boolean('deleted')) {
            $classSchedule->onlyTrashed();
        }


        $classSchedules =  $classSchedule->orderBy($sortBy ?? 'created_at', optional($request)->direction ?? 'desc');

        if ($otherSortBy == 'instructor_id') {
            $classSchedules->orderByRaw('(SELECT CONCAT(first_name," ",last_name) FROM admins WHERE admins.id = class_schedules.instructor_id)');
        } else if ($otherSortBy == 'class_id') {
            $classSchedules->orderByRaw('(SELECT name FROM class_lists WHERE class_lists.id = class_schedules.class_id)');
        } else {
            $classSchedules->orderBy($otherSortBy ?? 'start_at', 'asc');
        }

        return $classSchedules;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ClassSchedule $classSchedule)
    {
        $startOfWeek = $request->filled('startOfWeek') ? Carbon::parse($request->startOfWeek)->startOfWeek() : now()->startOfWeek();

        $classSchedules = $this->query($request, $classSchedule);

        $totalCost = $classSchedules->sum('cost');

        $classSchedules = $classSchedules->paginate(optional($request)->rowsPerPage ?? 15);

        return response()->json([
            'data' => $classSchedules->items(),
            'meta' => [
                'total' => $classSchedules->total(),
                'per_page' => $classSchedules->perPage(),
                'current_page' => $classSchedules->currentPage(),
                'last_page' => $classSchedules->lastPage(),
            ],
            'totalCost' => $totalCost,
            'startOfWeek' => $startOfWeek->format('Y-m-d'),
            'nextOfWeek' => $startOfWeek->addDays(7)->startOfWeek()->format('Y-m-d'),
            'previousOfWeek' => $startOfWeek->subDays(8)->startOfWeek()->format('Y-m-d'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassSchedule  $classSchedule
     * @return \Illuminate\Http\Response
     */
    public function show(ClassSchedule $classSchedule)
    {
        return response()->json($classSchedule->load(['active_bookings', 'stand_by_bookings', 'logs.admin']), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassSchedule  $classSchedule
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassSchedule $classSchedule)
    {
        if ($request->boolean('has_sign_off')) {
            $request->merge([
                'sign_off_at' => $classSchedule->sign_off_at ?? now(),
                'admin_id' => $classSchedule->admin_id ?? current_user()->id
            ]);
        } else {
            $request->merge([
                'sign_off_at' => null,
                'admin_id' => null
            ]);
        }
        // update the classSchedule
        $classSchedule->update($request->input());

        $bookings = array_merge($request->active_bookings ?? [], $request->stand_by_bookings ?? []);

        $classSchedule->syncBookings(collect($bookings));

        return response()->json([
            'data' => $classSchedule->fresh(['active_bookings', 'stand_by_bookings', 'logs.admin']),
            'message' => trans_module('updated', 'class_schedule')
        ], 200);
    }

    /**
     * Change sign_off of specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassSchedule  $classSchedule
     * @return \Illuminate\Http\Response
     */
    public function changeSignOff(Request $request, ClassSchedule $classSchedule)
    {
        $classSchedule->update([
            'sign_off_at' => $classSchedule->has_sign_off ? null : now()
        ]);

        return response()->json([
            'message' => trans('messages.class_schedule_sign_off', ['type' => !$classSchedule->has_sign_off ? 'marked' : 'unmarked']),
        ], 200);
    }

    /**
     * Print the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassSchedule  $classSchedule
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request, ClassSchedule $classSchedule)
    {
        return Pdf::loadView('pdfs.class-schedule', [
            'classSchedule' => $classSchedule
        ])->download("class-schedule-{$classSchedule->label}.pdf");
    }

    /**
     * Print a listing pdf of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listPdf(Request $request, ClassSchedule $classSchedule)
    {
        $startOfWeek = $request->filled('startOfWeek') ? Carbon::parse($request->startOfWeek)->startOfWeek() : now()->startOfWeek();

        $classSchedules = $this->query($request, $classSchedule);

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $from = Carbon::parse($request->input('date_from'));
            $to = Carbon::parse($request->input('date_to'));
            $date = $from->format('d/m/Y') . ' - ' . $to->format('d/m/Y');
        } else if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $date = $date->format('d/m/Y');
        } else {
            $date = $startOfWeek->format('d/m/Y');
        }

        $totalCost = $classSchedules->sum('cost');
        $classSchedules = $classSchedules->get();

        if (in_array($request->otherSortBy, ["class_id", "instructor_id"])) {
            $classSchedules = $classSchedules->groupBy(function ($item) use ($request) {
                return $item[$request->otherSortBy];
            })->map(function ($items) {
                $items->push([
                    'is_total' => true,
                    'cost' => $items->sum('cost')
                ]);
                return $items->values();
            });
            $classSchedules = $classSchedules->flatten(1)->values();
        }

        $time = now()->timestamp;
        return Pdf::loadView('pdfs.class-schedules', [
            'date' => $date,
            'total' => $totalCost,
            'classSchedules' => $classSchedules
        ])->setPaper('A4', 'landscape')->stream("class-schedules-{$time}.pdf");
    }

    public function calendar(Request $request, ClassSchedule $classSchedule)
    {
        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();
        $classSchedule = $classSchedule->whereBetween('date_at', [$start, $end]);

        if ($request->boolean('hasClass')) {
            $classSchedule->has('class');
        }

        $classSchedule = $classSchedule->orderBy('day_index')->orderBy('start_at')->get();

        return response()->json($classSchedule->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->instructor ? "{$item->class->name} - {$item->instructor->name}" : $item->class->name,
                'location' => optional($item->location)->label,
                'instructor' => optional($item->instructor)->name,
                'class' => optional($item->class)->name,
                'description' => $item->has_description ? $item->class->description : "",
                'start' => Carbon::parse("{$item->date_at->format('Y-m-d')} {$item->start_at}"),
                'end' => Carbon::parse("{$item->date_at->format('Y-m-d')} {$item->end_at}"),
                'color' => $item->has_sign_off ? '#dc3545' : '#ADFF2F',
                'url' => "/week-schedules/{$item->id}?action=edit"
            ];
        }), 200);
    }

    public function logs(Request $request, ClassSchedule $classSchedule)
    {
        $request->merge([
            'type' => 'notes',
        ]);

        $note = $classSchedule->logs()->create($request->input());

        return response()->json([
            'data' => $note->load('admin'),
            'message' => trans('coderstm::messages.users.note'),
        ], 200);
    }
}
