<?php

namespace App\Http\Controllers;

use App\Models\GuestPass;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class GuestPassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, GuestPass $guestPass)
    {
        $guestPass = $guestPass->query();

        if ($request->filled('filter')) {
            $guestPass->whereName($request->filter);
            $guestPass->orWhere('email', 'like', "%{$request->filter}%");
        }

        if ($request->boolean('deleted')) {
            $guestPass->onlyTrashed();
        }

        if (is_user()) {
            $guestPass->onlyOwner();
        }

        $guestPass = $guestPass->sortBy(optional($request)->sortBy ?? 'created_at', optional($request)->direction ?? 'desc')
            ->paginate(optional($request)->rowsPerPage ?? 15);
        return new ResourceCollection($guestPass);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, GuestPass $guestPass)
    {
        if (!current_user()->subscription()->canUseFeature('guest-pass')) {
            abort(403, trans('messages.guest_pass_no_credit'));
        }

        $rules = [
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules, [
            'email.unique' => trans('messages.guest_pass_unique_email'),
        ]);

        // create the guestPass
        $guestPass = GuestPass::create($request->input());

        return response()->json([
            'data' => $guestPass,
            'message' => trans_module('store', 'guest_pass'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GuestPass  $guestPass
     * @return \Illuminate\Http\Response
     */
    public function show(GuestPass $guestPass)
    {
        return response()->json($guestPass, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GuestPass  $guestPass
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GuestPass $guestPass)
    {

        $rules = [
            'title' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_number' => 'required',
        ];

        // Validate those rules
        $this->validate($request, $rules, [
            'email.unique' => trans('messages.guest_pass_unique_email'),
        ]);

        // update the guestPass
        $guestPass->update($request->input());

        return response()->json([
            'data' => $guestPass->fresh(),
            'message' => trans_module('updated', 'guest_pass'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GuestPass  $guestPass
     * @return \Illuminate\Http\Response
     */
    public function destroy(GuestPass $guestPass)
    {
        $guestPass->forceDelete();
        return response()->json([
            'message' => trans_module('destroy', 'guest_pass'),
        ], 200);
    }
}
