<?php

namespace App\Http\Controllers\User;

use App\Models\Booking;
use App\Events\BookingCanceled;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * Cancel the specified resource from storage.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\Response
     */
    public function cancel(Booking $booking)
    {
        $booking->cancel();
        event(new BookingCanceled($booking));
        return response()->json([
            'message' => trans('messages.booking_cancel'),
        ], 200);
    }
}
