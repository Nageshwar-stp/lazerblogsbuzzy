@if(get_buzzy_theme_config('PostPreviewShow')=='yes')
<figure class="content-body__image">
     <img class=" lazyload" data-src="{{ makepreview($post->thumb, 'b', 'posts') }}" alt="{{ $post->title }}" width="788" style="display: block;">
</figure>
@endif
<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <meta itemprop="url" content="{{ makepreview($post->thumb, 'b', 'posts') }}">
    <meta itemprop="width" content="788">
    <meta itemprop="height" content="443">
</div>

