<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\UserController;

class ReportController extends UserController
{

    public function reports(Request $request)
    {
        $args = $request->input();
        $rolling = User::getStatsByOptions('rolling', $args);
        $end_date = User::getStatsByOptions('end_date', $args);
        $cancelled = User::getStatsByOptions('cancelled', $args);
        $end_date_total =  User::getStatsByOptions('end_date_total', $args);
        $rolling_total = User::getStatsByOptions('rolling_total', $args);
        $cancelled_total = User::getStatsByOptions('cancelled_total', $args);

        return response()->json([
            'total' => User::getStatsByOptions('total', $args),
            'rolling' => $rolling,
            'rolling_total' => $rolling_total,
            'end_date' => $end_date,
            'end_date_total' => $end_date_total,
            'free' => User::getStatsByOptions('free', $args),
            'cancelled' => $cancelled,
            'cancelled_total' => $cancelled_total,
        ], 200);
    }

    public function reportsMonthly(Request $request)
    {
        return $this->reports($request);
    }

    public function reportsYearly(Request $request)
    {
        return $this->reports($request);
    }

    public function reportsDaily(Request $request)
    {
        return $this->reports($request);
    }

    public function pdf(Request $request)
    {
        return Pdf::loadView('pdfs.reports', $request->only(['rows', 'columns']))->download("reports-{$request->type}.pdf");
    }
}
