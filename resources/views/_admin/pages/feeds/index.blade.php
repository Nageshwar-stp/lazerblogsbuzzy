@extends("_admin.adminapp")
@section('header')
<link rel="stylesheet" href="{{ asset('assets/admin/css/menu.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/selectize/selectize.css') }}">
<link rel="stylesheet" href="{{ asset('assets/js/selectize/selectize.default.css') }}">
@endsection
@section("content")
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 style="display: flex;align-items:center">{{ trans('v4half.feeds') }}
        @if(get_buzzy_config('p_multilanguage') == 'on') &nbsp;>&nbsp; {!!
        Form::select('language', get_buzzy_language_list_options(), request()->query('lang', app()->getLocale()) , [
        "id"=>"changeLanguage",
        'class' => 'ml-2']) !!}
        @endif
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> {{ trans('admin.dashboard') }}</a></li>
        <li><a href="#">{{ trans('v4half.feeds') }}</a></li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-4">
            @include('_admin.pages.feeds.particles.feed-form', ['feed' => $feed])
        </div><!-- /.col -->

        <div class="col-md-8">
            <div class="dd" id="nestmenu">
                <div class="dd" id="nestmenu">
                    @include('_admin.pages.feeds.particles.feed-list', ['lists' => $feeds])
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section("footer")
<script src="{{ asset('assets/js/selectize/selectize.min.js') }}"></script>
<script>
    $('#changeLocation').on('change', function() {
        var val = $(this).val();
        location.href = '/admin/menus/' + val;
    });
    $('#changeLanguage').on('change', function() {
        var val = $(this).val();
        location.href = '/admin/feeds/?lang=' + val;
    });
    $("#post_categories").selectize({
        plugins: ["restore_on_backspace", "remove_button"],
        persist: false,
        delimiter: ',',
        maxItems: 10,
        valueField: "id",
        labelField: "name",
        searchField: ["id", "name"],
        create: false,
    });

    var xhr;
    $('#add_feed_post_users').selectize({
        plugins: ['remove_button'],
        valueField: 'id',
        labelField: 'username',
        searchField: ['username'],
        create: false,
        load: function(query, callback) {
            if (!query.length) return callback();

            xhr && xhr.abort();
            xhr = $.ajax({
                url: '{{action("SearchController@searchUsers")}}',
                type: 'GET',
                data: {
                    q: query,
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res);
                }
            });
        },
        onInitialize: function() {
            @if(isset($feed))
            @php($to_user = \App\User::find($feed->post_user_id))
            @if($to_user)
            this.addOption({
                id: "{{$to_user->id}}",
                username: '{{$to_user->username}}'
            });
            this.addItem("{{$to_user->id}}");
            @endif
            @endif
        },
    });
</script>
@endsection
