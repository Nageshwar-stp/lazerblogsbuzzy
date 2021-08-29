@if($post->type=='quiz')
@include('_particles._lists.quizentrylist')
@else
@foreach($entries as $key => $entry)
<section class="entry" id="section_{{ $entry->order }}" entry="{{ $entry->id }}">
    @if($entry->type=='poll')
    @if($entry->video)
    @include('_particles._lists.pollslist')
    @endif
    @else
    @if($entry->title)
    <h2 class="sub-title">
        @if($post->ordertype != '')
        {{ $entry->order+1 }}.
        @endif
        {{ $entry->title }}
    </h2>
    @endif
    @if($entry->type=='image')
    <div class="media">
        <div class="sharemedia">
            @include('._particles.others.entrysharebuttons')
        </div>
        <a id="" class="gif-icon-a"><img class="img-responsive"
                style="display: block;@if($entry->type=='image')width:100%@endif" alt="{{ $entry->title }}"
                src="{{ makepreview($entry->image, null, 'entries') }}"></a>
        <small>{!! $entry->source !!}</small>
    </div>
    @endif
    @if(in_array($entry->type, ['video','tweet','facebookpost','embed','soundcloud', 'instagram']))
    {!! parse_post_embed($entry->video, $entry->type) !!}
    @endif
    <p>
        {!! $entry->body !!}
    </p>
    @if( $entry->type=='text')
    <small>{!! $entry->source !!}</small>
    @endif
    <div class="clear"></div>
    @endif
</section>
@if($key==1 and count($entries) > 3)
@foreach(\App\Widgets::where('type', 'Post2nd3rdentry')->where('display', 'on')->get() as $widget)
{!! $widget->text !!}
@endforeach
@endif
@endforeach
@endif

@if(isset($post->pagination) and $post->pagination!=null)
<ul class="postpage">
    @if($entries->currentPage()!=1)
    <a href="{{ $entries->previousPageUrl() }}"
        class="button button-big button-blue pull-l ">{{ trans('pagination.previous') }}</a>
    @endif
    @if($entries->currentPage()!=$entries->lastPage())
    <a href="{{ $entries->nextPageUrl() }}"
        class="button button-big button-blue pull-r">{{ trans('pagination.next') }}</a>
    @endif
</ul>

@endif
