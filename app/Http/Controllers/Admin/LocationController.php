<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LocationController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Location::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Location $location)
    {
        $location = $location->query();

        if ($request->filled('filter')) {
            $location->where('label', 'like', "%{$request->filter}%");
        }

        if ($request->boolean('active')) {
            $location->onlyActive();
        }

        if ($request->boolean('deleted')) {
            $location->onlyTrashed();
        }

        $location = $location->orderBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($location);
    }

    /**
     * Display a options listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function options(Request $request, Location $location)
    {
        $request->merge([
            'option' => true
        ]);
        return $this->index($request, $location);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Location $location)
    {
        $rules = [
            'label' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // create the location
        $location = Location::create($request->input());

        return response()->json([
            'data' => $location,
            'message' => trans_module('store', 'location'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(Location $location)
    {
        return response()->json($location, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location)
    {
        $rules = [
            'label' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // update the location
        $location->update($request->input());

        return response()->json([
            'data' => $location->fresh(),
            'message' => trans_module('updated', 'location'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return response()->json([
            'message' => trans_module('destroy', 'location'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy_selected(Request $request, Location $location)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $location->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_modules('destroy', 'location'),
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Location::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_module('restored', 'location'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function restore_selected(Request $request, Location $location)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $location->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_modules('restored', 'location'),
        ], 200);
    }

    /**
     * Change active of specified resource from storage.
     *
     * @param  \App\Models\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function changeActive(Request $request, Location $location)
    {
        $location->update([
            'is_active' => !$location->is_active
        ]);

        return response()->json([
            'message' => trans_status('status', 'location', $location->is_active ? 'active' : 'deactive'),
        ], 200);
    }
}
