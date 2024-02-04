<script src="{{ asset('statics/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('statics/js/jquery-migrate-3.3.2.min.js') }}"></script>
<script src="{{ asset('statics/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('statics/js/jquery.easing.js') }}"></script>
<script src="{{ asset('statics/js/jquery-waypoints.js') }}"></script>
<script src="{{ asset('statics/js/jquery-validate.js') }}"></script>
<script src="{{ asset('statics/js/tether.min.js') }}"></script>
<script src="{{ asset('statics/js/jquery.prettyPhoto.js') }}"></script>
<script src="{{ asset('statics/js/numinate.min.js') }}"></script>
<script src="{{ asset('statics/js/imagesloaded.min.js') }}"></script>
<script src="{{ asset('statics/js/slick.min.js') }}"></script>
<script src="{{ asset('statics/js/jquery-isotope.js') }}"></script>
<script src="{{ asset('statics/js/main.js') }}"></script>
<script src="{{ asset('statics/js/fullcalendar/main.js') }}"></script>
<script>
    $('.mega-menu-link').click((evt) => {
        const {
            target
        } = evt
        if (target.href) {
            evt.preventDefault();
            window.open(target.href, "_self");
        }
    })
</script>
