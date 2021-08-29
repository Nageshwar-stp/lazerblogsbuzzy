@if($post->type=='quiz')

@include('_particles.lists.quizentrylist')

@else

@foreach($entries as $key => $entry)

<section class="entry fr-view" id="section_{{ $entry->order }}" entry="{{ $entry->id }}">

    @if($entry->type=='poll')

    @if($entry->video=='' or $entry->video==NULL)
    @else
    @include('_particles.lists.pollslist')
    @endif

    @else
    @if($entry->title)
    <h3 class="sub-title">

        @if($post->ordertype != '')
        {{ $entry->order+1 }}.
        @endif

        {{ $entry->title }}
    </h3>
    @endif

    @if($entry->type=='image')
    <img class="lazyload img-responsive" style="width:100%" data-src="{{ makepreview($entry->image, null, 'entries') }}"
        alt="{{ $entry->title }}">
    <small>{!! $entry->source !!}</small>
    @endif

    @if(in_array($entry->type, ['video','tweet','facebookpost','embed','soundcloud', 'instagram']))
    {!! parse_post_embed($entry->video, $entry->type) !!}
    @endif

    {!! $entry->body !!}

    @if( $entry->type=='text')
    <small>{!! $entry->source !!}</small>
    @endif
    <div class="clear"></div>

    @endif
</section>

@if($key==1 and count($entries) > 3)
@include('_particles.ads', ['position' => 'Post2nd3rdentry', 'width' => '788', 'height' => 'auto'])
@endif

@endforeach

@if(isset($post->pagination) and $post->pagination!=null)
<br>
<div class="clearfix"></div>
<ul class="postpage" style="padding: 0; margin: 0">
    @if($entries->currentPage()!=1)<a href="{{ generate_post_url($post).'?page='.($entries->currentPage()-1)  }}"
        class="button button-big button-blue pull-l ">{!! trans('pagination.previous') !!}</a>@endif
    @if($entries->currentPage()!=$entries->lastPage())<a
        href="{{ generate_post_url($post).'?page='.($entries->currentPage()+1) }}" style="float:right"
        class="button button-big button-blue pull-r">{!! trans('pagination.next') !!}</a>@endif
</ul>
<div class="clearfix"></div>
<br>
@endif

@endif
