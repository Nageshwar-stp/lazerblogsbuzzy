<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
@if( get_buzzy_config('site_default_text_editor', 'tinymce') == 'simditor')
<link rel="stylesheet" href="{{ asset('assets/plugins/editor/simditor.css')}}">
@elseif( get_buzzy_config('site_default_text_editor') == 'froala')
<link rel="stylesheet" href="{{ asset('assets/plugins/froala_editor/css/froala_editor.pkgd.min.css')}}">
@endif
