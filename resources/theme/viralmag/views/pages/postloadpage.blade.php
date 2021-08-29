<article role="main" itemscope itemtype="https://schema.org/NewsArticle" class="news__item" data-type="{{ $post->type }}" data-id="{{ $post->id }}" data-url="{{ generate_post_url($post) }}" data-title="{{ $post->title }}" data-description="{{ $post->body }}" data-keywords="" data-share="0">
   @include("_particles.post_schema_tags")
    <div class="content-body">
        <div class="content-body--left">
            <div class="content-sticky">
                @include("_particles.post_share_icons")
            </div>
        </div>
        <div class="content-body--right">
            @if($post->approve == 'draft')
                <div class="label label-staff" >{{ trans('updates.thisdraftpost') }}</div>
            @endif
            <div class="content-title">
                @include("_particles.post_badges")
                <h1 itemprop="headline"><a href="{{ generate_post_url($post) }}">{{ $post->title }}</a></h1>
            </div>

            @include("_particles.post_action_buttons")


            <div class="content-info">
               @include("_particles.post_author", ['show_views' => true])
            </div>

             @include("_particles.post_featured_image")

             @include('_particles.ads', ['position' => 'PostShareBw', 'width' => '788', 'height' => 'auto'])

            <div class="content-body__description" itemprop="description">{!! nl2br($post->body) !!}</div>

            <div class="content-body__detail" itemprop="articleBody">

                @include("_particles.lists.entryslists")

            </div>

            <!-- tags -->
            @include("_particles.post_tags", ['tags' => $post->tags])

            @include('_particles.ads', ['position' => 'PostBelow', 'width' => '788', 'height' => 'auto'])

            @include("_forms._reactionforms")

            @include("_particles.post_related_posts")

            @if(isset($commentson))
             @include("_particles.comments")
            @endif

        </div>
    </div> <div class="clear"></div>
</article>
