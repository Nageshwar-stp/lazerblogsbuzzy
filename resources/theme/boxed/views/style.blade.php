
    <style type="text/css">
        body {
            font-family: {!!    get_buzzy_theme_config('sitefontfamily', get_buzzy_config('sitefontfamily')) !!};
            background: {{  get_buzzy_theme_config('BodyBC') }}!important;
        }
        .header {
            background: {{ get_buzzy_theme_config('NavbarBC') }}!important;
        }
        .header__appbar_top_color {
            border-top: 3px solid {{ get_buzzy_theme_config('NavbarTBLC') }}!important;
        }
        .header__appbar_menu {
            background: {{ get_buzzy_theme_config('NavbarMenuBC') }}!important;
        }
        .header__appbar--left__nav{
            color: {{ get_buzzy_theme_config('NavbarMenuToogleC') }}!important;
        }
        .header__appbar--left__menu__list__item > a{
            color: {{ get_buzzy_theme_config('NavbarLC') }}!important;
        }
        .header__appbar--left__menu__list__item > a > i{
            color: {{ get_buzzy_theme_config('NavbarLC') }}!important;
        }
        .header__appbar--left__menu__list__item > a:hover{
            color: {{ get_buzzy_theme_config('NavbarLHC') }}!important;
        }
        .header__appbar--left__menu__list__item > a:hover > i{
            color: {{ get_buzzy_theme_config('NavbarLHC') }}!important;
        }
        .button.button-create {
            background: {{ get_buzzy_theme_config('NavbarCBBC') }}!important;
            color: {{ get_buzzy_theme_config('NavbarCBFC') }}!important;
            border-color: {{ get_buzzy_theme_config('NavbarCBBC') }}!important;
        }
        .button.button-create i {
            color: {{ get_buzzy_theme_config('NavbarCBFC') }}!important;
        }
        .button.button-create:hover {
            background: {{ get_buzzy_theme_config('NavbarCBHBC') }}!important;
            color: {{ get_buzzy_theme_config('NavbarCBHFC') }}!important;
        }
        .button.button-create:hover i {
            color: {{ get_buzzy_theme_config('NavbarCBHFC') }}!important;
        }
    </style>
