@if(isset($show_categories) && $show_categories)
<div class="item_category">
    @foreach ($post->categories()->get() as $item)
    <a href="{{action('PagesController@showCategory', ['catname' => $item->name_slug ])}}" class="seca"
        style="margin-right:5px">
        {{$item->name}}</a>
    @endforeach
</div>
@endif
