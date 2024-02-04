@extends('layouts.page')
@section('content')
    <section class="ttm-row contact-section clearfix">
        <div class="container">
            <!-- row -->
            <div class="row box-shadow ttm-bgcolor-white p-50 no-gutters">
                <div class="col-lg-5 res-991-padding_bottom30">
                    <img class="img-fluid" src="{{ asset('statics/images/blog/5.jpg') }}" alt="blog-img">
                </div>
                <div class="col-lg-7">
                    <div class="contact-side res-991-margin_left0">
                        <div class="section-title clearfix">
                            <div class="title-header">
                                {{-- <h4>CONTACT NOW</h4> --}}
                                <h2 class="title">Contact Us</h2>
                            </div>
                        </div>
                        <p>We’re here to help and answer any question you might have. We look forward to hearing from you.
                            Feel free to contact us</p>
                        <div class="padding_top10">
                            <form id="contactUsForm" class="request_qoute_form wrap-form clearfix" method="post"
                                novalidate="novalidate">
                                @csrf
                                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                                <div class="row ttm-boxes-spacing-20px">
                                    <div class="col-md-6">
                                        <label>
                                            <span class="text-input">
                                                <input class="@error('name') is-invalid @enderror" name="name"
                                                    type="text" value="" placeholder="Name" required="required">
                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label>
                                            <span class="text-input">
                                                <input class="@error('phone') is-invalid @enderror" name="phone"
                                                    type="text" value="" placeholder="Phone Number"
                                                    required="required">
                                                @error('phone')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>
                                            <span class="text-input">
                                                <input class="@error('email') is-invalid @enderror" name="email"
                                                    type="text" value="" placeholder="Email" required="required">
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                        </label>
                                    </div>
                                    <div class="col-md-12">
                                        <label>
                                            <span class="text-input">
                                                <textarea class="@error('message') is-invalid @enderror" name="message" rows="4"
                                                    placeholder="Leave a comment or query" required="required"></textarea>
                                                @error('message')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="pt-15 mb_20 text-center">
                                            <button id="submit"
                                                class="ttm-btn ttm-btn-size-md ttm-btn-shape-rounded ttm-btn-style-fill ttm-btn-color-skincolor z-index-1 w-100"
                                                type="submit">SEND NOW!</button>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div id="form-success" style="display: none" class="alert alert-success"
                                            role="alert">
                                            Message sent. We will contact you soon.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('style')
    <style>
        .grecaptcha-badge {
            display: none;
        }
    </style>
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>
    <script>
        var ReCaptchaCallbackV3 = function() {
            grecaptcha.ready(function() {
                grecaptcha.execute('{{ config('recaptcha.site_key') }}').then(function(token) {
                    document.getElementById("recaptcha_token").value = token;
                });
            });
        };
        ReCaptchaCallbackV3()
    </script>
    <script>
        $("#contactUsForm").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 50
                },
                email: {
                    required: true,
                    maxlength: 50,
                    email: true,
                },
                message: {
                    required: true,
                    maxlength: 300
                },
            },
            messages: {
                name: {
                    required: "Please enter name",
                    maxlength: "Your name maxlength should be 50 characters long."
                },
                phone: {
                    required: "Please enter valid contact number",
                    maxlength: "Your name maxlength should be 50 characters long."
                },
                email: {
                    required: "Please enter valid email",
                    email: "Please enter valid email",
                    maxlength: "The email name should less than or equal to 50 characters",
                },
                message: {
                    required: "Please enter message",
                    maxlength: "Your message name maxlength should be 300 characters long."
                },
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#submit').html('Please Wait...');
                $("#submit").attr("disabled", true);
                $.ajax({
                    url: "{{ route('contact.submit') }}",
                    type: "POST",
                    data: $('#contactUsForm').serialize(),
                    success: function({
                        message
                    }) {
                        $('#submit').html('SEND NOW!');
                        $("#submit").attr("disabled", false);
                        $("#form-success").removeClass('alert-danger').addClass('alert-success')
                            .text(message).show();
                        document.getElementById("contactUsForm").reset();
                        ReCaptchaCallbackV3()
                        setTimeout(() => {
                            $("#form-success").hide();
                        }, 10000);
                    },
                    error: function(response) {
                        const {
                            message
                        } = response.responseJSON;
                        ReCaptchaCallbackV3()
                        $("#form-success").removeClass('alert-success').addClass('alert-danger')
                            .text(message).show();
                        setTimeout(() => {
                            $("#form-success").hide();
                        }, 10000);
                    }
                });
            }
        })
    </script>
@endsection
