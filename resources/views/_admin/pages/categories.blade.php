@extends("_admin.adminapp")
@section("content")
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ trans('admin.Categories') }}
        @if(get_buzzy_config('p_multilanguage') == 'on') &nbsp;>&nbsp; {!!
        Form::select('language', get_buzzy_language_list_options(), request()->query('lang', app()->getLocale()) , [
        "id"=>"changeLanguage",
        'class' => 'ml-2']) !!}
        @endif
    </h1>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('admin.dashboard') }}</a></li>
        <li class="active">{{ trans('admin.Categories') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-4">
            @include('_admin._particles.categories._categoryforms')
        </div><!-- /.col -->

        <div class="col-md-8">
            @foreach($categories as $ci => $categorys)
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right" style="border-bottom: 0;">
                    <li class="pull-left header">

                        {!! '<i class="fa fa-' . config('buzzy.post_types.' . $categorys->type. '.icon') . '"></i>' !!}

                        <b>{{ $categorys->name }}</b>

                        @if($categorys->disabled === "1")
                        <span class="pull-right badge bg-red" data-toggle="tooltip"
                            data-original-title="Category Disabled"
                            STYLE="margin-top:7px;margin-left:10px">DISABLED</span>
                        @endif
                        @if(!array_key_exists($categorys->type, get_post_types()))
                        <span class="pull-right badge bg-red" data-toggle="tooltip"
                            data-original-title="Post type: {{ $categorys->type }} deactivated on Plugins. Users can't add {{ $categorys->type }} post type"
                            STYLE="margin-top:7px;margin-left:10px">Not available</span>
                        @endif
                    </li>
                    <li class="pull-right header">
                        <a href="/admin/categories?edit={{ $categorys->id }}&lang={{request()->query('lang', config('app.locale'))}}"
                            class="btn btn-sm btn-success" style="    display: inline-block;padding:0" role="button"
                            data-toggle="tooltip" title="" data-original-title="{{ trans("admin.edit") }}"><i
                                class="fa fa-edit"></i>
                            {{ trans('admin.edit') }}</a>
                        <a class="btn btn-sm btn-danger permanently"
                            href="{{ url('admin/categories/delete/'.$categorys->id) }}"
                            style="    display: inline-block;padding:0" role="button" data-toggle="tooltip"
                            data-original-title="{{ trans("admin.delete") }}"><i class="fa fa-times"></i>
                            {{ trans('admin.delete') }}</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_{{ $ci }}-1">

                        @include('_admin._particles.categories._categorylist', ['altcategories' => $categorys])

                    </div><!-- /.tab-pane -->

                </div>
            </div>
            @endforeach
        </div><!-- /.col -->
    </div><!-- /.row -->
</section>
@endsection
@section("footer")
<script>
    $('#changeLanguage').on('change', function(){
        var menu =$('#changeLocation').val(),
            val = $(this).val();
        location.href = '/admin/categories/?lang='+val;
    });

</script>
@endsection
