@extends('layouts.main')
@section('content')
    @if ($offers->count() > 0)
        <div class="banner_slider_wrapper">
            <div class="banner_slider banner_slider_2">
                @foreach ($offers as $offer)
                    @php
                        $thumbnail = $offer['thumbnail'] ? $offer['thumbnail']['url'] : asset('statics/images/slider-image-111.png');
                    @endphp
                    <div class="slide" style="background-image: url({{ $thumbnail }});">
                        <div class="slide__content ttm-textcolor-white text-center slide_style1">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="slide__content--headings d-block text-center">
                                            <div class="padding_left30 padding_top10 padding_bottom10">
                                                @if ($offer['tag_line'])
                                                    <h3 data-animation="fadeInDown"
                                                        style="@if (isset($offer['options']['tag_line'])) color: {{ $offer['options']['tag_line'] }} !important; @endif"
                                                        class="highlight_round text-center">
                                                        {{ $offer['tag_line'] }}
                                                    </h3>
                                                @endif
                                                @if ($offer['title_line_1'])
                                                    <h2 data-animation="fadeInDown"
                                                        style="@if (isset($offer['options']['title_line'])) color: {{ $offer['options']['title_line'] }} !important; @endif"
                                                        class="text-center">
                                                        {{ $offer['title_line_1'] }}
                                                    </h2>
                                                @endif
                                                @if ($offer['title_line_2'])
                                                    <h2 data-animation="fadeInDown"
                                                        style="@if (isset($offer['options']['title_line'])) color: {{ $offer['options']['title_line'] }} !important; @endif"
                                                        class="text-center">
                                                        {{ $offer['title_line_2'] }}
                                                    </h2>
                                                @endif
                                                @if ($offer['link'])
                                                    <div class="margin_top30 align-items-center res-767-margin_top20"
                                                        data-animation="fadeInUp" data-delay="1.4">
                                                        <a style="@if (isset($offer['options']['button'])) color: {{ $offer['options']['button'] }} !important; @endif"
                                                            class="ttm-btn ttm-btn-size-md ttm-btn-shape-square ttm-btn-style-fill ttm-icon-btn-right ttm-btn-color-skincolor"
                                                            href="{{ $offer['link'] }}">{{ $offer['button'] ?? 'Read More' }}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <section class="ttm-row clearfix">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="about-img ml_278 res-1199-ml-0 res-991-mr-0">
                        <img class="img-fluid" src="{{ asset('statics/images/single-img-4.png') }}" alt="">
                    </div>
                </div>
                <div class="col-lg-6 padding_top25 padding_left55 res-991-padding_left15">
                    <div class="res-991-mt-50">
                        <div class="section-title padding_bottom0 clearfix">
                            <div class="title-header">
                                <h4>ABOUT US</h4>
                                <h2 class="title">Introducing NitroFIT28 a different Fitness Centre</h2>
                            </div>
                        </div>
                        <p>
                            Our Gym, Studios, Fitness Centre is based in our wonderful Grade II building with
                            modern facilities throughout the three floor layout. We focus on providing the best members
                            experience possible which start with our well maintained private grounds and car parking through
                            to our fitness and wellbeing areas within the building.
                        </p>
                        <p>
                            We operate a NO Contract membership arrangement, see our membership page for more information.
                        </p>
                        <p>
                            Our Reception is warm and welcoming which would not look out of place in a high end spa along
                            with our members area. Our focus is our members, from a welcoming smile and assistance during
                            each and every visit. We want to ensure every member feels valued and special as part of our
                            NitroFIT28 community.
                        </p>
                        <ul
                            class="padding_top15 ttm-list ttm-list-style-icon font-weight-normal ttm-list-icon-color-highlight mb-10">
                            <li>
                                <i class="fa fa-check-circle-o"></i>
                                <div class="ttm-list-li-content">The Functional Training Zone on the ground floor.</div>
                            </li>
                            <li>
                                <i class="fa fa-check-circle-o"></i>
                                <div class="ttm-list-li-content">Fitness and Wellbeing Studio on the first floor.</div>
                            </li>
                            <li>
                                <i class="fa fa-check-circle-o"></i>
                                <div class="ttm-list-li-content">Cycling Studio on the first floor.</div>
                            </li>
                            <li>
                                <i class="fa fa-check-circle-o"></i>
                                <div class="ttm-list-li-content">Modern and Luxury Changing Facilities</div>
                            </li>
                            <li>
                                <i class="fa fa-check-circle-o"></i>
                                <div class="ttm-list-li-content">Members have access to their own NitroFIT28 space via
                                    members App to allow them to book and monitor their wellness journey.</div>
                            </li>
                        </ul>
                        <div class="margin_top30 align-items-center res-767-margin_top20">
                            <a class="ttm-btn ttm-btn-size-md ttm-btn-shape-square ttm-btn-style-fill ttm-icon-btn-right ttm-btn-color-skincolor"
                                href="javascript:void(0)">Get Started </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ttm-row ttm-bgcolor-grey">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <div class="title-header text-center">
                            <h2>WHY <img style="width: 110px;margin-top: -6px;margin-left: 3px;"
                                    src="{{ asset('images/logo-alt.png') }}" alt="">
                            </h2>
                            <h2 class="title">Our Members Deserve More</h2>
                        </div>
                    </div>
                    <div class="row slick_slider-1">
                        <div class="col-md-4 col-sm-6">
                            <!--featured-imagebox-->
                            <div class="featured-imagebox featured-imagebox-services style1">
                                <!-- featured-thumbnail -->
                                <div class="featured-thumbnail">
                                    <a href="analytic-solutions.html"><img width="742" height="618" class="img-fluid"
                                            src="{{ asset('statics/images/single-img-21.png') }}" alt="image"></a>
                                    <div class="ttm-blog-overlay-iconbox">
                                        <a href="javascript:void(0);" tabindex="0">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                    <div class="cat_block-wrapper">
                                        <span class="cat_block">
                                            <time class="entry-date published" datetime="2020-11-11T04:34:34+00:00">
                                                GET READY
                                            </time>
                                        </span>
                                    </div>
                                </div><!-- featured-thumbnail end-->
                                <div class="featured-content featured-content-post">
                                    <div class="featured-title">
                                        <h3>
                                            <a href="javascript:void(0);">
                                                Ground Floor
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="featured-desc">
                                        <p>
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book.
                                            <br>
                                            &nbsp;
                                        </p>
                                    </div>

                                    <div class="ttm-boxbg-icon">
                                        <div class="ttm-service-iconbox">
                                            <div class="ttm-icon">
                                                <img style="width: 65px" src="{{ asset('images/logo-alt.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- featured-imagebox end-->
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <!--featured-imagebox-->
                            <div class="featured-imagebox featured-imagebox-services style1">
                                <!-- featured-thumbnail -->
                                <div class="featured-thumbnail">
                                    <a href="analytic-solutions.html"><img width="742" height="618" class="img-fluid"
                                            src="{{ asset('statics/images/single-img-22.png') }}" alt="image"></a>
                                    <div class="ttm-blog-overlay-iconbox">
                                        <a href="javascript:void(0);" tabindex="0">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                    <div class="cat_block-wrapper">
                                        <span class="cat_block">
                                            <time class="entry-date published" datetime="2020-11-11T04:34:34+00:00">
                                                SET YOUR GOAL
                                            </time>
                                        </span>
                                    </div>
                                </div><!-- featured-thumbnail end-->
                                <div class="featured-content featured-content-post">
                                    <div class="featured-title">
                                        <h3>
                                            <a href="javascript:void(0);">
                                                First Floor
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="featured-desc">
                                        <p>
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book.
                                            <br>
                                            &nbsp;
                                        </p>
                                    </div>

                                    <div class="ttm-boxbg-icon">
                                        <div class="ttm-service-iconbox">
                                            <div class="ttm-icon">
                                                <img style="width: 65px" src="{{ asset('images/logo-alt.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- featured-imagebox end-->
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <!--featured-imagebox-->
                            <div class="featured-imagebox featured-imagebox-services style1">
                                <!-- featured-thumbnail -->
                                <div class="featured-thumbnail">
                                    <a href="analytic-solutions.html"><img width="742" height="618"
                                            class="img-fluid" src="{{ asset('statics/images/single-img-20.png') }}"
                                            alt="image"></a>
                                    <div class="ttm-blog-overlay-iconbox">
                                        <a href="javascript:void(0);" tabindex="0">
                                            <i class="ti ti-plus"></i>
                                        </a>
                                    </div>
                                    <div class="cat_block-wrapper">
                                        <span class="cat_block">
                                            <time class="entry-date published" datetime="2020-11-11T04:34:34+00:00">
                                                ACHIEVE YOUR DREAMS
                                            </time>
                                        </span>
                                    </div>
                                </div><!-- featured-thumbnail end-->
                                <div class="featured-content featured-content-post">
                                    <div class="featured-title">
                                        <h3>
                                            <a href="javascript:void(0);">
                                                Second Floor
                                            </a>
                                        </h3>
                                    </div>
                                    <div class="featured-desc">
                                        <p>
                                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem
                                            Ipsum has been the industry's standard dummy text ever since the 1500s, when an
                                            unknown printer took a galley of type and scrambled it to make a type specimen
                                            book.
                                            <br>
                                            &nbsp;
                                        </p>
                                    </div>

                                    <div class="ttm-boxbg-icon">
                                        <div class="ttm-service-iconbox">
                                            <div class="ttm-icon">
                                                <img style="width: 65px" src="{{ asset('images/logo-alt.png') }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- featured-imagebox end-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
