<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClassList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClassListController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(ClassList::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ClassList $classList)
    {
        $classList = $classList->query();

        if ($request->filled('filter')) {
            $classList->where('name', 'like', "%{$request->filter}%");
        }

        if ($request->boolean('active')) {
            $classList->onlyActive();
        }

        if ($request->boolean('deleted')) {
            $classList->onlyTrashed();
        }

        $classList = $classList->orderBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($classList);
    }

    /**
     * Display a options listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, ClassList $classList)
    {
        $request->merge([
            'option' => true
        ]);
        return $this->index($request, $classList);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ClassList $classList)
    {
        $rules = [
            'name' => 'required',
            'capacity' => 'required',
            'description' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // create the classList
        $classList = ClassList::create($request->input());

        return response()->json([
            'data' => $classList,
            'message' => trans_module('store', 'class'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function show(ClassList $classList)
    {
        return response()->json($classList, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassList $classList)
    {

        $rules = [
            'name' => 'required',
            'capacity' => 'required',
            'description' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // update the classList
        $classList->update($request->input());

        return response()->json([
            'data' => $classList->fresh(),
            'message' => trans_module('updated', 'class'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassList $classList)
    {
        $classList->delete();
        return response()->json([
            'message' => trans_module('destroy', 'class'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function destroy_selected(Request $request, ClassList $classList)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $classList->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_modules('destroy', 'class'),
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        ClassList::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_module('restored', 'class'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function restore_selected(Request $request, ClassList $classList)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $classList->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_modules('restored', 'class'),
        ], 200);
    }

    /**
     * Change active of specified resource from storage.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function changeActive(Request $request, ClassList $classList)
    {
        $classList->update([
            'is_active' => !$classList->is_active
        ]);

        return response()->json([
            'message' => trans_status('status', 'class', $classList->is_active ? 'active' : 'deactive'),
        ], 200);
    }

    /**
     * Change active of specified resource from storage.
     *
     * @param  \App\Models\ClassList  $classList
     * @return \Illuminate\Http\Response
     */
    public function changeHasDescription(Request $request, ClassList $classList)
    {
        $classList->update([
            'has_description' => !$classList->has_description
        ]);

        return response()->json([
            'message' => $classList->has_description ? trans('messages.class_description_visiable') : trans('messages.class_description_hide'),
        ], 200);
    }
}
