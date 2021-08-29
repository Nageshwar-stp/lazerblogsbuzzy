@if(get_buzzy_theme_config('PostPageAutoload') == 'related')
    @if(isset($lastFeatures))
        @if(count($lastFeatures) >= 3)
                <br>
            <div class="colheader sea">
                <h1>{{ trans('index.maylike') }}</h1>
            </div>
            @include("_particles.lists.may-like-posts")
        @endif
    @endif
@endif
