<footer
    class="footer widget-footer ttm-textcolor-white ttm-bgcolor-darkgrey clearfix padding_top30 res-991-padding_top0">
    <div class="ttm-row-wrapper-bg-layer ttm-bg-layer"></div>
    <div class="second-footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-5 widget-area">
                    <div class="widget widget_text clearfix">
                        <div class="footer-logo">
                            <img id="footer-logo-img" class="img-center" src="{{ asset('images/logo.png') }}"
                                alt="{{ config('app.name') }}">
                        </div>
                        <div class="textwidget widget-text">
                            <p class="pb-10 pr-30">Fitness and Wellbeing is a journey, lifestyle, work life balance or
                                an interest that improves our lives.</p>
                        </div>
                        <div class="order-3">
                            <div class="social-icons square">
                                <ul class="social-icons list-inline">
                                    <li>
                                        <a class="tooltip-top" href="#" rel="noopener" aria-label="facebook"
                                            data-tooltip="Facebook">
                                            <i class="ti ti-facebook"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="tooltip-top" href="#" rel="noopener" aria-label="instagram"
                                            data-tooltip="Instagram">
                                            <i class="ti ti-instagram"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="tooltip-top" href="#" rel="noopener" aria-label="youtube"
                                            data-tooltip="Youtube">
                                            <i class="ti ti-youtube"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="tooltip-top" href="mailto:{{ config('app.email') }}" rel="noopener"
                                            aria-label="email" data-tooltip="Email">
                                            <i class="fa fa-envelope"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-area col-xs-12 col-sm-6 col-md-6 col-lg-4">
                    <div class="widget widget_text clearfix">
                        <h3 class="widget-title">Get In Touch</h3>
                        <div class="textwidget widget-text">
                            <ul class="widget_contact_wrapper">
                                <li>
                                    <i class="ttm-textcolor-skincolor fa fa-map-marker"></i>
                                    {{ company_address() }}
                                </li>
                                <li>
                                    <i class="ttm-textcolor-skincolor fa fa-envelope-o"></i>
                                    <a href="mailto:{{ config('app.email') }}">{{ config('app.email') }}</a>
                                </li>
                                <li>
                                    <i class="ttm-textcolor-skincolor fa fa-phone"></i>
                                    <a href="tel:{{ config('app.phone') }}">{{ config('app.phone') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="widget-area col-xs-12 col-sm-6 col-md-6 col-lg-3">
                    <div class="widget widget_nav_menu clearfix">
                        <h3 class="widget-title">Information</h3>
                        <ul id="menu-footer-quick-links" class="menu">
                            <li>
                                <a href="{{ route('about') }}">About Us</a>
                            </li>
                            <li>
                                <a href="{{ route('contact') }}">Contact Us</a>
                            </li>
                            <li>
                                <a href="{{ route('classes') }}">Classes</a>
                            </li>
                            <li>
                                <a href="{{ route('membership') }}">Membership</a>
                            </li>
                            <li>
                                <a href="{{ route('opening-times') }}">Opening Times</a>
                            </li>
                            <li>
                                <a href="{{ route('documents') }}">Documents</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bottom-footer-text copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-md-flex justify-content-between">
                        <span>Copyright © 2021&nbsp;<a href="index.html">{{ config('app.name') }}</a>. All rights
                            reserved.</span>
                        <ul class="footer-nav-menu">
                            <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                            <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<a id="totop" href="#top" class="top-visible" style="display: inline;">
    <i class="fa fa-angle-up"></i>
</a>
