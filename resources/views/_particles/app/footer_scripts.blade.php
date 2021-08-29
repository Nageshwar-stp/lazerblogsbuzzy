<script src="{{ asset('assets/js/manifest.js') }}"></script>
<script src="{{ asset('assets/js/vendor.js') }}"></script>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script>
    $(function() {
        //facebook
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{ get_buzzy_config("facebookapp") }}',
                xfbml: true,
                version: 'v11.0'
            });
            FB.XFBML.parse();
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/{{ get_buzzy_config('sitelanguage', 'en_US') }}/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));

    });
</script>

@include('errors.swalerror')

<div id="auth-modal" class="modal auth-modal"></div>

<div id="fb-root"></div>

<div class="hide">
    <input name="_requesttoken" id="requesttoken" type="hidden" value="{{ csrf_token() }}" />
</div>
