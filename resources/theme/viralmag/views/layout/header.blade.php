<header class="header">
    <div class="header__searchbar">
        <div class="container">
            <div class="header__searchbar__container">
                <form action="{{ action('SearchController@index') }}" method="get">
                    <input class="header__searchbar__container__input" id="search" type="search" required="" name="q"
                        placeholder="{{ trans('index.search') }}" autocomplete="off">
                    <label class="header__searchbar__container__close material-button material-button--icon ripple"
                        for="search"><i class="material-icons">&#xE5CD;</i></label>
                </form>
            </div>
        </div>
    </div>
    <div class="header__appbar header__appbar_top_color">
        <div class="container">
            <div class="header__appbar--left">
                <div class="header__appbar--left__nav visible-mobile">
                    <i class="material-icons" style="font-weight: 700;">menu</i>
                </div>
                <div class="header__appbar--left__logo"><a href="/"><img class="site-logo"
                            src="{{ asset(get_buzzy_config('sitelogo')) }}" alt=""></a></div>

            </div>
            <div class="header__appbar--right hide-mobile">

                <div class="reaction-emojis">
                    @foreach(\App\Reaction::where('display', 'on')->orderBy('ord', 'asc')->get() as $reaction)
                    <a href="{{ action('PagesController@showReaction', ['reaction' => $reaction->reaction_type] ) }}"
                        title="{{ $reaction->name }}"><img alt="{{ $reaction->name }}" src="{{ $reaction->icon }} "
                            width="42"></a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
    <div class="header__appbar header__appbar_menu">
        <div class="container">
            <div class="header__appbar--left hide-mobile">
                <div class="header__appbar--left__menu ">
                    @include('_particles.header_menu')
                </div>
            </div>
            <div class="header__appbar--right">
                <div class="header__appbar--right__search">
                    <div class="header__appbar--right__search__button material-button material-button--icon ripple"><i
                            class="material-icons">&#xE8B6;</i></div>
                </div>
                <div class="header__appbar--right__notice">
                    @include('_particles.header_create_button')
                </div>
                @include('_particles.header_user')
            </div>
        </div>
    </div>

</header>

@include('layout.header_mobile')
