<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Coderstm\Models\File;
use Coderstm\Models\Plan;
use App\Models\Announcement;
use App\Models\Enquiry;
use Illuminate\Http\Request;
use Coderstm\Models\AppSetting;
use Coderstm\Rules\ReCaptchaRule;

class PageController extends Controller
{
    public function index()
    {
        $offers = AppSetting::findByKey('banners')->where('is_active', true);
        return view('pages.home.2', [
            'offers' => $offers
        ]);
    }

    public function membership()
    {
        $plans = Plan::onlyActive()->get()->map(function ($item) {
            return array_merge($item->toArray(), [
                'cur_symbol' => currency_symbol()
            ]);
        });
        return view('pages.membership', [
            'title' => trans('pages.titles.membership'),
            'subtitle' => trans('pages.subtitles.service'),
            'background' => 'title-row-2',
            'plans' => $plans,
        ]);
    }

    public function openingTimes(Request $request)
    {
        $announcements = Announcement::active()->paginate(10);
        $openingTimes = opening_times();
        return view('pages.opening-times', [
            'title' => trans('pages.titles.opening_times'),
            'subtitle' => trans('pages.subtitles.company'),
            'background' => 'title-row-2',
            'announcements' => $announcements,
            'openingTimes' => $openingTimes,
        ]);
    }

    public function documents()
    {
        $documents = File::whereIn('id', AppSetting::findByKey('documents')
            ->where('is_active', true)
            ->where('member', false)
            ->pluck('id'))->get();
        return view('pages.documents', [
            'title' => trans('pages.titles.documents'),
            'subtitle' => trans('pages.subtitles.company'),
            'background' => 'title-row-2',
            'documents' => $documents,
        ]);
    }

    public function classes(Request $request)
    {
        return view("pages.classes", [
            'title' => trans('pages.titles.classes'),
            'subtitle' => trans('pages.subtitles.schedule'),
            'background' => 'title-row-2',
        ]);
    }

    public function about()
    {
        return view('pages.about-us', [
            'title' => trans('pages.titles.about_us'),
            'subtitle' => trans('pages.subtitles.about_us'),
            'background' => 'title-row-2',
        ]);
    }

    public function contact()
    {
        return view('pages.contact-us', [
            'title' => trans('pages.titles.contact_us'),
            'subtitle' => trans('pages.subtitles.contact_us'),
            'background' => 'title-row-2',
        ]);
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
            'message' => 'required',
            'recaptcha_token' => ['required', new ReCaptchaRule()]
        ]);

        Enquiry::create($request->only([
            'email',
            'name',
            'phone',
            'message',
        ]));

        return response()->json([
            'success' => true,
            'message' => trans('messages.contact_success_submit')
        ], 200);
    }

    public function terms()
    {
        return view('pages.terms', [
            'title' => trans('pages.titles.terms_conditions'),
            'subtitle' => trans('pages.subtitles.company'),
            'background' => 'title-row-2',
        ]);
    }

    public function privacy()
    {
        return view('pages.privacy', [
            'title' => trans('pages.titles.privacy_policy'),
            'subtitle' => trans('pages.subtitles.company'),
            'background' => 'title-row-2',
        ]);
    }

    public function partners()
    {
        return view('pages.partners', [
            'title' => trans('pages.titles.partners'),
            'subtitle' => trans('pages.subtitles.company'),
            'background' => 'title-row-2',
        ]);
    }

    public function plans()
    {
        return Plan::with('prices')->onlyActive()->get();
    }
}
