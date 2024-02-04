<?php

namespace App\Http\Controllers\Admin;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class AnnouncementController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Announcement::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Announcement $announcement)
    {
        $announcement = $announcement->query();

        if ($request->filled('filter')) {
            $announcement->where('note', 'like', "%{$request->filter}%");
            $announcement->orWhere('date', 'like', "%{$request->filter}%");
        }

        if ($request->boolean('deleted')) {
            $announcement->onlyTrashed();
        }

        $announcement = $announcement->orderBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($announcement);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Announcement $announcement)
    {
        $rules = [
            'date' => 'required',
            'note' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // create the announcement
        $announcement = Announcement::create($request->input());

        return response()->json([
            'data' => $announcement,
            'message' => trans_module('store', 'announcement'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        return response()->json($announcement, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {

        $rules = [
            'date' => 'required',
            'note' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules);

        // update the announcement
        $announcement->update($request->input());

        return response()->json([
            'data' => $announcement->fresh(),
            'message' => trans_module('updated', 'announcement'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return response()->json([
            'message' => trans_module('destroy', 'announcement'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy_selected(Request $request, Announcement $announcement)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $announcement->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_modules('destroy', 'announcement'),
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Announcement::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_module('restored', 'announcement'),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function restore_selected(Request $request, Announcement $announcement)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $announcement->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_modules('restored', 'announcement'),
        ], 200);
    }
}
