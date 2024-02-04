<?php

namespace App\Http\Controllers\Admin;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstructorController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Instructor::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Instructor $instructor)
    {
        $instructor = $instructor->query();

        if ($request->filled('filter')) {
            $instructor->where(DB::raw("CONCAT(first_name,' ',last_name)"), 'like', "%{$request->filter}%");
            $instructor->orWhere('email', 'like', "%{$request->filter}%");
        }

        if ($request->filled('is_pt')) {
            $instructor->where('is_pt', $request->is_pt);
        }

        if ($request->filled('class')) {
            $instructor->whereHas('classes', function ($query) use ($request) {
                $query->where('id', $request->class);
            });
        }

        if ($request->filled('status')) {
            $instructor->where('status', $request->input('status'));
        }

        if ($request->boolean('active')) {
            $instructor->onlyActive();
        }

        if ($request->boolean('deleted')) {
            $instructor->onlyTrashed();
        }

        $instructor = $instructor->sortBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($instructor);
    }

    /**
     * Display a options listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, Instructor $instructor)
    {
        $request->merge([
            'option' => true
        ]);
        return $this->index($request, $instructor);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Instructor $instructor)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:admins',
            'status' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // create the instructor
        $instructor = Instructor::create($request->input());

        $instructor = $this->saveRelated($request, $instructor);

        return response()->json([
            'data' => $instructor,
            'message' => trans_module('store', 'instructor'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function show(Instructor $instructor)
    {
        return response()->json($instructor->load('classes', 'insurance_file', 'qualification_file', 'document_file'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Instructor $instructor)
    {

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|unique:admins,email,' . $instructor->id,
            'status' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // update the instructor
        $instructor->update($request->input());

        $instructor = $this->saveRelated($request, $instructor);

        return response()->json([
            'data' => $instructor,
            'message' => trans_module('updated', 'instructor'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Instructor $instructor)
    {
        $instructor->delete();
        return response()->json([
            'message' => trans_module('destroy', 'instructor'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy_selected(Request $request, Instructor $instructor)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $instructor->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_modules('destroy', 'instructor'),
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Instructor::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_module('restored', 'instructor'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function restore_selected(Request $request, Instructor $instructor)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $instructor->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_modules('restored', 'instructor'),
        ], 200);
    }

    function saveRelated(Request $request, Instructor $instructor): Instructor
    {
        if ($request->filled('classes')) {
            $instructor = $instructor->syncClasses(collect($request->classes));
        }

        foreach ([
            'avatar' => 'avatar',
            'insurance_file' => 'insurance',
            'qualification_file' => 'qualification',
            'document_file' => 'document',
        ] as $item => $type) {
            if ($request->filled($item)) {
                $instructor->$item()->sync([
                    $request->input("$item.id") => [
                        'type' => $type
                    ]
                ]);
            }
        }

        return $instructor->fresh('classes', 'avatar', 'insurance_file', 'qualification_file', 'document_file');
    }
}
