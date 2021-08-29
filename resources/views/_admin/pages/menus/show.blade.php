@extends("_admin.adminapp")
@section('header')
<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/nestable/jquery.nestable.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/admin/css/menu.css') }}" />
@endsection
@section("content")
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 style="display: flex;align-items:center">{{ trans('v4.menus') }}&nbsp;>&nbsp;{!! Form::select('current',
        \App\Menu::all()->pluck('name', 'id'), $menu->id , [ "id"=>"changeLocation", 'class' => 'ml-2'])
        !!}
        @if(get_buzzy_config('p_multilanguage') == 'on') &nbsp;>&nbsp; {!!
        Form::select('language', get_buzzy_language_list_options(), request()->query('lang',
        get_buzzy_config('sitedefaultlanguage')) , [
        "id"=>"changeLanguage",
        'class' => 'ml-2']) !!}
        @endif
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-dashboard"></i> {{ trans('admin.dashboard') }}</a></li>
        <li><a href="#">{{ trans('v4.menus') }}</a></li>
        <li class="active">{{ $menu->name }}</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-4">
            @include('_admin.pages.menus.particles.menu-item-form', ['menu' => $menu])
        </div><!-- /.col -->

        <div class="col-md-8">
            <div class="dd" id="nestmenu">
                @include('_admin.pages.menus.particles.draggable-menu', ['lists' =>
                $menu->items()->where('language', request()->query('lang',
                get_buzzy_config('sitedefaultlanguage')))->whereNull('parent_id')->orderBy('order', 'asc')->get()])
            </div>
        </div>
    </div>
</section>
@endsection
@section("footer")
<script src="{{ asset('assets/plugins/adminlte/plugins/nestable/jquery.nestable.min.js') }}"></script>

<script>
    $('#changeLocation').on('change', function(){
        var val = $(this).val();
        location.href = '/admin/menus/'+val;
    });
    $('#changeLanguage').on('change', function(){
        var menu =$('#changeLocation').val(),
            val = $(this).val();
        location.href = '/admin/menus/'+menu+'?lang='+val;
    });

     $("#nestmenu").nestable({
        group: 1,
        maxDepth: {{ menu_settings($menu->id)['depth'] }},
        callback: function(l,e){
            // l is the main container
            // e is the element that was moved
            var list   = l.length ? l : $(l.target);
            var menus = list.nestable('toArray');
            console.log(menus);
            $.ajax({
                url: '/admin/menus/menu/item/sort',
                method: 'POST',
                responseType: 'json',
                data: {
                    'menus': menus,
                    '_token': "{{csrf_token()}}"
                },
                success: function(res){
                    if (res.success == true) {
                        console.log('Sorted Successfully.');
                    }
                }
            });
        }
    });
</script>
@endsection
