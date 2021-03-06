<div class="drawer">
    <div class="drawer__header clearfix">
        <div class="drawer__header__logo"><a href="/"><img src="{{ asset(get_buzzy_config('sitelogo')) }}" alt=""></a>
        </div>
        <span class="drawer__header__close"><i class="material-icons">&#xE408;</i></span>
    </div>
    <ul class="drawer__menu">
        <li class="drawer__menu__item drawer__menu__item--active">
            <a class="drawer__menu__item__link" href="/">
                <span class="drawer__menu__item__icon"><i class="material-icons">&#xE88A;</i></span>
                <span class="drawer__menu__item__title">{{ trans('updates.home') }}</span>
            </a>
        </li>
        {{ menu('mobile-menu', array(
            'ul' => false,
            'li_class' => 'drawer__menu__item clearfix',
            'a_class' => 'drawer__menu__item__link',
            'icon_class' => 'drawer__menu__item__icon',
            'title_class' => 'drawer__menu__item__title'
        )) }}

        <li class=" drawer__menu__item--border ">
            <div class="reaction-emojis" style="padding:20px 10px">
                @foreach(\App\Reaction::where('display', 'on')->orderBy('ord', 'asc')->get() as $reaction)
                <a href="{{ action('PagesController@showReaction', ['reaction' => $reaction->reaction_type] ) }}"
                    title="{{ $reaction->name }}"><img alt="{{ $reaction->name }}" src="{{ $reaction->icon }} "
                        width="42"></a>
                @endforeach
            </div>
        </li>
    </ul>

    <div class="footer-left " style="width:100%;padding:10px">
        <div class="footer-menu clearfix">
            @foreach(\App\Pages::where('footer', '1')->get() as $page)
            <a class="footer-menu__item " style="color:#888"
                href="{{ action('PagesController@showpage', [$page->slug ]) }}"
                title="{{ $page->title }}">{{ $page->title }}</a>
            @endforeach
            @if(get_buzzy_config('p_buzzycontact') == 'on')
            <a class="footer-menu__item" style="color:#888"
                href="{{ action('ContactController@index') }}">{{ trans('buzzycontact.contact') }}</a>
            @endif
        </div>
        <div class="footer-copyright clearfix" style="color:#aaa">
            {{ trans("updates.copyright") }}

        </div>
    </div>
</div>
