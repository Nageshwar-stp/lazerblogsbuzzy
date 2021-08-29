 <div class="clearfix" itemprop="author" itemscope itemtype="https://schema.org/Person" >
    @if(isset($post->user->username_slug))
        <!-- publisher -->
        <div>
            <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                <meta itemprop="url" content="{{ makepreview($post->user->icon , 'b', 'members/avatar') }}">
                <meta itemprop="width" content="200">
                <meta itemprop="height" content="200">
            </div>
            <meta itemprop="name" content="{{ $post->user->username }}">
            @if(isset($post->user->facebook))<meta itemprop="sameAs" content="{{ $post->user->facebook }}">@endif
            @if(isset($post->user->twitter))<meta itemprop="sameAs" content="{{ $post->user->twitter }}">@endif
        </div>

        <div class="user-info {{ $post->user->genre }} answerer">
            <div class="avatar left">
                <img src="{{ makepreview($post->user->icon , 's', 'members/avatar') }}" width="45" height="45" alt="{{ $post->user->username }}">
            </div>
            <div class="info">
                <a itemprop="name" class="content-info__author" href="{{ action('UsersController@index', ['userslug' => $post->user->username_slug ]) }}" target="_self">{{ $post->user->username}}</a>
                @if($post->user->usertype == 'Admin')
                    <div class="label label-admin" style="margin-left:5px">{{ trans('updates.usertypeadmin') }}</div>
                @elseif($post->user->usertype == 'Staff')
                    <div class="label label-staff" style="margin-left:5px">{{ trans('updates.usertypestaff') }}</div>
                @elseif($post->user->usertype == 'banned')
                    <div class="label label-banned" style="margin-left:5px">{{ trans('updates.usertypebanned') }}</div>
                @endif
                <div class="detail">
                    {!! trans('index.postedon', ['time' => '<time class="content-info__date">'.$post->published_at->diffForHumans() .'</time>' ]) !!}
                    @if($post->updated_at->getTimestamp() > $post->published_at->getTimestamp())
                        <em class="content-info__line">—</em> {!! trans('index.updatedon', ['time' => '<time class="content-info__date">'.$post->updated_at->diffForHumans() .'</time>' ]) !!}
                    @endif
                </div>
            </div>
        </div>

    @if(isset($show_views))
    <div class="content-share__view" style="float:right;color:#888;width:60px;text-align:right;font-size:13px;margin-top: 2px">
        <b>{{ isset($post->popularityStats->all_time_stats) ? number_format($post->popularityStats->all_time_stats) : "0" }}</b><br> {{ trans('updates.views') }}
    </div>
    @endif

    @include('_particles.post_categories')
    @endif
</div>
