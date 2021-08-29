@if ($tags = $post->tags()->get())
<div class="content-tag hide-mobiles">
    @foreach($tags as $tag)
    <span class="tagy"><a href="{{ action('TagController@index', $tag->slug) }}">{{$tag->name}}</a></span>
    @endforeach
</div>
@endif
