<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Enquiry;
use Coderstm\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Coderstm\Http\Controllers\ApplicationController as Controller;

class ApplicationController extends Controller
{
    /**
     * Get stats.
     *
     * @return \Illuminate\Http\Response
     */
    public function stats(Request $request)
    {
        $user = User::select('users.id', "subscriptions.created_at")->leftJoin('subscriptions', function ($join) {
            $join->on('subscriptions.user_id', '=', "users.id");
        })->whereNotNull('subscriptions.created_at');
        return response()->json([
            'total' => User::getStats('total'),
            'rolling' => User::getStats('rolling'),
            'end_date' => User::getStats('end_date'),
            'monthly' => User::getStats('month'),
            'yearly' => User::getStats('year'),
            'free' => User::getStats('free'),
            'max_year' => $user->max(DB::raw("DATE_FORMAT(subscriptions.created_at,'%Y')")),
            'min_year' => 2015,
            'unread_support' => Enquiry::onlyActive()->count(),
            'unread_tasks' => Task::onlyActive()->count(),
        ], 200);
    }
}
