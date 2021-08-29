@if(get_buzzy_config('p_amp') === 'on')
@if($post->type == 'news' || $post->type == 'list' || $post->type == 'video' )
<link rel="amphtml" href="{{ url('amp/'.$post->type.'/'.$post->id) }}">
@endif
@endif
<meta property="og:image:width" content="780" />
<meta property="og:image:height" content="440" />
@if( get_buzzy_config('site_default_text_editor', 'tinymce') == 'froala')
<link rel="stylesheet" href="{{ asset('assets/plugins/froala_editor/css/froala_style.min.css')}}">
@endif
