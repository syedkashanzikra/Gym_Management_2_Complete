<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use Coderstm\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EnquiryController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(Enquiry::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Enquiry $enquiry)
    {
        $enquiry = $enquiry->query();

        if ($request->filled('filter')) {
            $enquiry->where('subject', 'like', "%{$request->filter}%")
                ->orWhere('email', 'like', "%{$request->filter}%");
        }

        if ($request->filled('type')) {
            $enquiry->whereType($request->type);
        }

        $enquiry->onlyStatus($request->status);

        if ($request->boolean('deleted')) {
            $enquiry->onlyTrashed();
        }

        if (is_user()) {
            $enquiry->onlyOwner();
        }

        $enquiry = $enquiry->sortBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?: 15);
        return new ResourceCollection($enquiry);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Enquiry $enquiry)
    {
        $rules = [
            'subject' => 'required',
            'message' => 'required',
            'user' => 'required_if:admin,true|array',
        ];

        $this->validate($request, $rules);

        $request->merge([
            'source' => !$request->boolean('admin')
        ]);

        if ($request->boolean('bulk')) {
            collect($request->input('user'))->each(function ($user) use ($enquiry, $request) {
                $request->merge([
                    'name' => $user['name'],
                    'email' => $user['email'],
                ]);

                $enquiry = $enquiry->create($request->input());

                // Update media
                if ($request->filled('media')) {
                    $enquiry = $enquiry->syncMedia($request->input('media'));
                }
            });

            return response()->json([
                'message' => trans('coderstm::messages.enquiry.store'),
            ], 200);
        }

        if ($request->filled('user')) {
            $request->merge([
                'name' => $request->input('user.name'),
                'email' => $request->input('user.email'),
            ]);
        }

        $enquiry = $enquiry->create($request->input());

        // Update media
        if ($request->filled('media')) {
            $enquiry = $enquiry->syncMedia($request->input('media'));
        }

        return response()->json([
            'data' => $enquiry->load(['user', 'replies.user', 'media', 'admin']),
            'message' => trans('coderstm::messages.enquiry.store'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Enquiry $enquiry)
    {
        $enquiry = $enquiry->markedAsSeen();
        return response()->json($enquiry->load(['user', 'replies.user', 'media', 'order', 'admin']), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Enquiry $enquiry)
    {
        $enquiry->delete();
        return response()->json([
            'message' => trans_choice('coderstm::messages.enquiry.destroy', 1),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function destroySelected(Request $request, Enquiry $enquiry)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $enquiry->whereIn('id', $request->items)->each(function ($item) {
            $item->delete();
        });
        return response()->json([
            'message' => trans_choice('coderstm::messages.enquiry.destroy', 2),
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        Enquiry::onlyTrashed()
            ->where('id', $id)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_choice('coderstm::messages.enquiry.restored', 1),
        ], 200);
    }

    /**
     * Remove the selected resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function restoreSelected(Request $request, Enquiry $enquiry)
    {
        $this->validate($request, [
            'items' => 'required',
        ]);
        $enquiry->onlyTrashed()
            ->whereIn('id', $request->items)->each(function ($item) {
                $item->restore();
            });
        return response()->json([
            'message' => trans_choice('coderstm::messages.enquiry.restored', 2),
        ], 200);
    }

    /**
     * Create reply for the specified resource.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, Enquiry $enquiry)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $reply = $enquiry->createReply($request->input());

        // Update media
        if ($request->filled('media')) {
            $reply = $reply->syncMedia($request->input('media'));
        }

        // Update enquiry status
        if ($request->filled('status')) {
            $enquiry->update($request->only(['status']));
        }

        return response()->json([
            'data' => $reply->fresh(['media', 'user']),
            'message' => trans('coderstm::messages.enquiry.reply'),
        ], 200);
    }

    /**
     * Change archived of specified resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function changeArchived(Request $request, Enquiry $enquiry)
    {
        $enquiry->update([
            'is_archived' => !$enquiry->is_archived
        ]);

        $type = !$enquiry->is_archived ? 'archived' : 'unarchive';

        return response()->json([
            'message' => trans('coderstm::messages.enquiry.status', ['type' => trans('coderstm::messages.attributes.' . $type)]),
        ], 200);
    }

    /**
     * Change user archived of specified resource from storage.
     *
     * @param  \App\Models\Enquiry  $enquiry
     * @return \Illuminate\Http\Response
     */
    public function changeUserArchived(Request $request, Enquiry $enquiry)
    {
        $enquiry->update([
            'user_archived' => !$enquiry->user_archived
        ]);

        $type = !$enquiry->is_archived ? 'archived' : 'unarchive';

        return response()->json([
            'message' => trans('coderstm::messages.enquiry.status', ['type' => trans('coderstm::messages.attributes.' . $type)]),
        ], 200);
    }
}
