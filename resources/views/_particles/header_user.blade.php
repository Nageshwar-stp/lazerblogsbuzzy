 @if(Auth::check())
 @php($message_count = auth()->user()->newThreadsCount())
    <div class="header__appbar--right__settings">

        <div class="header__appbar--right__settings__button has-dropdown" data-target="settings-dropdown" data-align="right-bottom">
            <div class=" material-button material-button--icon ripple ">
                <img src="{{ makepreview(Auth::user()->icon, 's', 'members/avatar') }}" width="34" height="34"  alt="{{ Auth::user()->username }}">
            </div>
            @if($message_count)
                <span class="badge-count">{{$message_count}}</span>
            @endif
        </div>
        <div class="settings-dropdown dropdown-container" style="width: 150px;">
            <ul>
                <li class="dropdown-container__item ripple">
                    <a href="{{ action('UsersController@index', [ Auth::user()->username_slug ]) }}">{{ trans('index.myprofile') }}</a>
                </li>
                <li class="dropdown-container__item ripple">
                    <a href="{{ action('UserMessageController@index', [ Auth::user()->username_slug ]) }}">{{ trans('v4.messages') }}</a>
                    @if($message_count)
                        <span class="badge-count">{{$message_count}}</span>
                    @endif
                </li>
                <li class="dropdown-container__item ripple">
                    <a href="{{ action('UsersController@updatesettings', ['userslug' => Auth::user()->username_slug ]) }}">{{ trans('index.settings') }}</a>
                </li>
                <li class="dropdown-container__item ripple">
                    <a href="{{ action('UsersController@followfeed', ['userslug' => Auth::user()->username_slug ]) }}">{{ trans('updates.feedposts') }}</a>
                </li>
                <li class="dropdown-container__item ripple">
                    <a href="{{ action('UsersController@draftposts', ['userslug' => Auth::user()->username_slug ]) }}">{{ trans('index.draft') }}</a>
                </li>
                <li class="dropdown-container__item ripple">
                    <a href="{{ action('UsersController@deletedposts', ['userslug' => Auth::user()->username_slug ]) }}">{{ trans('index.trash') }}</a>
                </li>
                @if(Auth::user()->usertype=='Admin')
                <li class="dropdown-container__item ripple">
                    <a href="/admin" target="_blank">{{ trans('index.adminp') }}</a>
                </li>
                @endif
                <li class="dropdown-container__item ripple">
                    <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                            {{ trans('index.logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                </li>
            </ul>
        </div>
    </div>
@else
    <div class="header__appbar--right__settings">
        <a class="header__appbar--right__settings__button material-button material-button--icon ripple"  href="{{ route('login') }}" rel="get:Loginform">
            <i class="material-icons">&#xE853;</i>
        </a>
    </div>
@endif
