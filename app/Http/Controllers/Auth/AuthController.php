<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateParqRequest;
use App\Notifications\AvatarAttachedNotification;
use Coderstm\Http\Controllers\Auth\AuthController as Controller;

class AuthController extends Controller
{
    public function me($guard = 'users')
    {
        $user = current_user()->fresh([
            'address',
            'lastLogin'
        ]);

        if (guard() == 'users') {
            $user = $user->load(['parq', 'blocked'])->loadUnreadEnquiries()->toArray();
        } else if (guard() == 'admins') {
            $user = $user->append('modules')->toArray();
        }

        return response()->json($user, 200);
    }

    public function update(Request $request, $guard = 'users')
    {
        $user = current_user();

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'address.line1' => 'required',
            'address.city' => 'required',
            'address.postal_code' => 'required',
            'address.country' => 'required',
            'email' => "email|unique:{$guard},email,{$user->id}",
        ];

        if ($guard == 'users') {
            $rules['title'] = 'required';
        }

        // Validate those rules
        $this->validate($request, $rules);

        $user->update($request->only([
            'title',
            'first_name',
            'last_name',
            'email',
            'phone_number',
        ]));

        // add address to the user
        $user->updateOrCreateAddress($request->input('address'));

        if ($request->filled('avatar')) {
            $user->avatar()->sync([
                $request->input('avatar.id') => [
                    'type' => 'avatar'
                ]
            ]);
            $user->update([
                'request_avatar' => false
            ]);
            admin_notify(new AvatarAttachedNotification($user->fresh('avatar')));
        }

        return $this->me($guard);
    }

    public function updateParq(UpdateParqRequest $request)
    {
        current_user()->updateOrCreateParq($request->input());

        current_user()->update([
            'request_parq' => 0
        ]);

        $user = current_user()->fresh(['parq']);

        return response()->json([
            'data' =>  $user->parq,
            'message' => trans('messages.parq_updated'),
        ], 200);
    }
}
