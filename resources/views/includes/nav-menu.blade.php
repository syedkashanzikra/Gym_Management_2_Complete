<nav class="main-menu menu-mobile" id="menu">
    <ul class="menu">
        <li class="mega-menu-item {{ is_active('/') }}">
            <a href="{{ route('home') }}" class="mega-menu-link">
                Home
            </a>
        </li>
        <li class="mega-menu-item {{ is_active('classes*') }}">
            <a href="{{ route('classes') }}" class="mega-menu-link">
                Classes
            </a>
        </li>
        <li class="mega-menu-item {{ is_active('membership*') }}">
            <a href="{{ route('membership') }}" class="mega-menu-link">
                Membership
            </a>
        </li>
        <li class="mega-menu-item {{ is_active('about', 'contact', 'documents', 'opening-times') }}">
            <a href="javascript:void(0);" class="mega-menu-link">Company</a>
            <ul class="mega-submenu">
                <li class="{{ is_active('about') }}">
                    <a href="{{ route('about') }}">
                        About Us
                    </a>
                </li>
                <li class="{{ is_active('contact') }}">
                    <a href="{{ route('contact') }}">
                        Contact Us
                    </a>
                </li>
                <li class="{{ is_active('documents') }}">
                    <a href="{{ route('documents') }}">
                        Documents
                    </a>
                </li>
                <li class="{{ is_active('opening-times') }}">
                    <a href="{{ route('opening-times') }}">
                        Opening Times
                    </a>
                </li>
            </ul>
        </li>
        <li class="mega-menu-item">
            <a href="{{ config('coderstm.member_url') }}" class="mega-menu-link">
                Members Login
            </a>
        </li>
    </ul>
</nav>
