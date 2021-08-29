<!doctype html>
<html lang="{{ Lang::getLocale() }}">

<head>
    @include("_particles.app.head_meta_tags")

    <link
        href="https://fonts.googleapis.com/css?family={{  get_buzzy_theme_config('googlefont', get_buzzy_config('googlefont', 'Roboto')) }}"
        rel='stylesheet' type='text/css'>
    <link href="{{ asset(get_buzzy_config('favicon')) }}" rel="shortcut icon" type="image/x-icon" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/theme/default/css/style'. get_buzzy_rtl_prefix() .'.css') }}">

    @include("style")

    @yield("header")

    @include("_particles.app.head_code")
</head>

<body class="{{ str_replace('-', '', get_buzzy_rtl_prefix()) }} {{ get_buzzy_config('LayoutType') }} {{ get_buzzy_config('NavbarType') }}
    @yield(" body_class") @yield("modeboxed") ">
@include(" layout.header") <div class="content-wrapper" id="container-wrapper">
    @if(!Request::is('create') ) @if(Request::segment(1)!=='profile') @if(Request::segment(1)!=='edit')
    @foreach(\App\Widgets::where('type', 'HeaderBelow')->where('display', 'on')->get() as $widget)
    <div class="content">
        <div class="container" style="text-align: center;padding-top:20px;padding-bottom:20px ">
            <center>
                {!! $widget->text !!}
            </center>
        </div>
    </div>
    @endforeach
    @endif @endif @endif
    @yield("content")

    </div>

    @include("layout.footer")

    <div id="fb-root"></div>

    <script src="{{ asset('assets/js/manifest.js') }}"></script>
    <script src="{{ asset('assets/js/vendor.js') }}"></script>
    <script src="{{ asset('assets/theme/default/js/app.min.js') }}"></script>


    @yield("footer")
    @include('.errors.swalerror')

    <div id="auth-modal" class="modal auth-modal"></div>

    <div class="hide">
        <input name="_requesttoken" id="requesttoken" type="hidden" value="{{ csrf_token() }}" />
    </div>

    @include("_particles.app.footer_code")

</body>

</html>
