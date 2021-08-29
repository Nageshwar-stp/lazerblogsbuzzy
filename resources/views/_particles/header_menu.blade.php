{{ menu('main-menu', array(
    'ul_class' => 'header__appbar--left__menu__list',
    'li_class' => 'header__appbar--left__menu__list__item'
)) }}
<ul class="header__appbar--left__menu__list">
    <li class="header__appbar--left__menu__list__item">
        <a class="category-dropdown-button ripple has-dropdown" href="javascript:" data-target="category-dropdown"
            data-align="center">
            <i class="material-icons">&#xE5D3;</i>
        </a>
        <div class="category-dropdown dropdown-container">
            <div class="category-dropdown_sec sec_cat1 clearfix">
                <div class="category-dropdown_community">
                    <div class="community_title">{{ trans('updates.heycommunity') }}</div>
                    <div class="community_desc"> @if(Auth::check()) {!! trans('updates.heycommunitydesc2') !!} @else {!!
                        trans('updates.heycommunitydesc') !!} @endif</div>
                </div>

                <div class="reaction-emojis">
                    @php ($reactions = \App\Reaction::where('display', 'on')->orderBy('ord', 'asc')->get())
                    @if (count($reactions) > 0)
                    @foreach($reactions as $reaction)
                    <a href="{{ action('PagesController@showReaction', ['reaction' => $reaction->reaction_type] ) }}"
                        title="{{ $reaction->name }}"><img alt="{{ $reaction->name }}" src="{{ $reaction->icon }} "
                            width="42"></a>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="category-dropdown_sec sec_cat2 clearfix">
                @php(menu('mega-menu', array(
                'ul_class' => '',
                'li_class' => 'dropdown-container__item ripple'
                )))
            </div>
            <div class="category-dropdown_sec sec_cat3 clearfix">
                <img class="footer-site-logo" src="{{ asset(get_buzzy_config('footerlogo')) }}" width="60px" alt="">
                @include('_particles.header_language_picker')

                <div class="footer-left">
                    <div class="footer-menu clearfix">
                        @php(menu('footer-menu', array(
                        'ul_class' => '',
                        'li_class' => 'footer-menu__item'
                        )))
                        @if(get_buzzy_config('p_buzzycontact') == 'on')
                        <a class="footer-menu__item"
                            href="{{ action('ContactController@index') }}">{{ trans('buzzycontact.contact') }}</a>
                        @endif
                    </div>
                    <div class="footer-copyright clearfix">
                        {{ trans("updates.copyright") }}
                    </div>
                </div>
            </div>
        </div>
    </li>
</ul>
