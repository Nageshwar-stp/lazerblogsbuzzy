<div class="clear"></div>


<footer class="footer">
    <div class="container" style="border:0 ; box-shadow: none;">
        <div class="row">
            <ul>
                @foreach(\App\Pages::where('footer', '1')->get() as $page)
                <li> <a href="{{ action('PagesController@showpage', [$page->slug ]) }}"
                        title="{{ $page->title }}">{{ $page->title }}</a></li>
                @endforeach
                @if(get_buzzy_config('p_buzzycontact') == 'on')
                <li> <a href="{{ action('ContactController@index') }}">{{ trans('buzzycontact.contact') }}</a></li>
                @endif
            </ul>
            <div class="col-1">


                <div class="social-side">

                    @if(get_buzzy_config('facebookpage'))<a target="_blank"
                        href="{!!  get_buzzy_config('facebookpage') !!}"><i class="fa fa-facebook-square"></i></a>
                    @endif
                    @if(get_buzzy_config('twitterpage'))<a target="_blank"
                        href="{!!  get_buzzy_config('twitterpage') !!}"><i class="fa fa-twitter"></i></a>@endif
                    @if(get_buzzy_config('googlepage'))<a target="_blank"
                        href="{!!  get_buzzy_config('googlepage') !!}"><i class="fa fa-google-plus"></i></a>@endif
                    @if(get_buzzy_config('instagrampage'))<a target="_blank"
                        href="{!!  get_buzzy_config('instagrampage') !!}"><i class="fa fa-instagram"></i></a>@endif
                    <a href="/index.xml"><i class="fa fa-rss"></i></a>

                </div>

            </div>




            <div class="col-2">


            </div>

            <div class="col-3">

            </div>

        </div>
    </div>
    <div class="clear"></div>
</footer>
<div class="clear"></div>
<footer class="footer" style="margin:0;background:#F9F9F9;border-top:1px solid #f1f1f1">

    <div class="container" style="border:0 ;box-shadow: none;">
        <div class="row">


            <div class="col-1">
                <img class="site-logo" src="{{ asset(get_buzzy_config('footerlogo')) }}" alt="">

                @include('_particles.header_language_picker')
                <div class="clear"></div>
            </div>


            <div class="col-3" style="padding-top:10px;">
                {{ trans("updates.copyright") }}

            </div>

        </div>
        <div class="clear"></div>
    </div>
</footer>
