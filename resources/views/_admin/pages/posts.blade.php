@extends("_admin.adminapp")
@section('header')
<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/datatables/dataTables.bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/datatables/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/adminlte/plugins/iCheck/flat/blue.css') }}">

<style>
    .table-actions-menu {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        z-index: 20;
        background-color: #fff;
        height: 60px;
        padding: 10px 15px;
        width: 100%;
    }

    .table tbody td {
        position: relative
    }

    .table tbody td div.icheckbox_flat-blue {
        position: absolute;
        top: 50%;
        margin-top: -12px;
    }
</style>
@endsection
@section("content")
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        @if(Request::query('only')=='unapprove')
        {!! trans('admin.Unapproved', ['type' => $title ]) !!}
        @elseif(Request::query('only')=='deleted')
        {!! trans("admin.Trash", ['type' => $title]) !!}
        @else
        {{ $title }}
        @endif
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {!! trans("admin.dashboard") !!}</a></li>
        <li class="active">{{ $title }}</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">

            <div class="box">
                <div class="overlay hide">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div class="box-body">
                    <div class="table-actions-menu">
                        <div class="btn-group">
                            @if(Request::query('only')=='deleted')
                            <button type="button" class="btn btn-success dropdown-toggle doaction"
                                data-url="{{ action("Admin\PostsController@deletePost", ['action' => 'restore']) }}"
                                data-type="move" data-toggle="dropdown" aria-expanded="true"><span class="fa fa-recycle"
                                    style="margin-right:5px"></span> {{ trans("admin.RetrievefromTrash") }} </button>
                            @else
                            <button type="button" class="btn btn-danger dropdown-toggle doaction"
                                data-url="{{ action("Admin\PostsController@deletePost", ['action' => 'remove']) }}"
                                data-type="move" data-toggle="dropdown" aria-expanded="true"><span class="fa fa-trash"
                                    style="margin-right:5px"></span> {{ trans("admin.SendtoTrash") }} </button>
                            @endif
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                                aria-expanded="true">{{ trans("buzzycontact.Actions") }} <span class="fa fa-caret-down"
                                    style="margin-left:5px"></span></button>
                            <ul class="dropdown-menu pull-left"
                                style="left:0px;  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);">
                                @if(Request::query('only')!='deleted')
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@approvePost", ['action' => 'yes']) }}"
                                        data-type="do" data-action="Approve"><i class="fa fa-check text-green"></i>
                                        {{ trans("admin.Approve") }}</a></li>
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@approvePost", ['action' => 'no']) }}"
                                        data-type="do" data-action="UndoApprove"><i class="fa fa-check"></i>
                                        {{ trans("admin.UndoApprove") }}</a></li>
                                <li class="divider"></li>
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@setFeatured", ['action' => 'yes']) }}"
                                        data-type="do" data-action="PickforFeatured"><i
                                            class="fa fa-star text-yellow"></i> {{ trans("admin.PickforFeatured") }}</a>
                                </li>
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@setFeatured", ['action' => 'no']) }}"
                                        data-type="do" data-action="UndoFeatured"><i class="fa fa-star"></i>
                                        {{ trans("admin.UndoFeatured") }}</a></li>
                                <li class="divider"></li>
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@setForHomepage", ['action' => 'yes']) }}"
                                        data-type="do" data-action="PickforHomepage"><i
                                            class="fa fa-dashboard text-red"></i>
                                        {{ trans("admin.PickforHomepage") }}</a></li>
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@setForHomepage", ['action' => 'no']) }}"
                                        data-type="do" data-action="UndofromHomepage"><i class="fa fa-dashboard"></i>
                                        {{ trans("admin.UndofromHomepage") }}</a></li>
                                <li class="divider"></li>
                                @endif
                                <li><a href="javascript:;" class="doaction"
                                        data-url="{{ action("Admin\PostsController@forceDeletePost", ['action' => 'remove']) }}"
                                        data-type="deleteperma" data-action="deleteperma"><i class="fa fa-remove"></i>
                                        {{ trans("admin.Deletepermanently") }}</a></li>
                            </ul>
                        </div>
                    </div>
                    <table id="table" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <div class="cho">
                                        <input type="checkbox" class="checkbox-toggle">
                                    </div>
                                </th>
                                <th>{!! trans("admin.Preview") !!}</th>
                                <th>{!! trans("admin.Title") !!}</th>
                                <th>{!! trans("admin.User") !!}</th>
                                <th>{!! trans("admin.Status") !!}</th>
                                @if(get_buzzy_config('p_multilanguage') == 'on')
                                <th>{!! trans("v4.post_language") !!}</th>
                                @endif
                                @if($type=='features')
                                <th>{!! trans("admin.FeaturedDate") !!}</th>
                                @else
                                <th>{!! trans("admin.Dates") !!}</th>
                                @endif
                                <th>{!! trans("admin.Actions") !!}</th>
                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
@endsection
@section('footer')
<!-- iCheck -->
<script src="{{ asset('assets/plugins/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
<script src="{{ asset('assets/plugins/adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/adminlte/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/adminlte/plugins/datatables/dataTables.responsive.min.js') }}"></script>
@php($only = (Request::query('only') ? '&only=' . Request::query('only') : '') )
<script>
    $(document).ready(function() {
        function toggle_table_actions() {
            var selected_count = $(".table").find('td :checked').length;

            if (selected_count > 0) {
                $(".table-actions-menu").show();
            } else {
                $(".table-actions-menu").hide();
            }
        }

        function do_post_action(data_url){
            $(".overlay").removeClass('hide');

            $.ajax({
                type: "GET",
                dataType: 'json',
                url: data_url , // This is the URL to the API
                success: function(data) {
                    setTimeout(function() {
                        table.api().ajax.reload();
                    }, 500);
                    setTimeout(function() {
                        $(".cho input[type='checkbox']").iCheck("uncheck");
                        $(".cho .fa").removeClass("fa-check-square-o").addClass('fa-square-o');
                        $(".table-actions-menu").removeClass('loading');
                        $(".overlay").addClass('hide');
                        toggle_table_actions();
                    }, 1000);
                },
                error: function(data) {
                    swal({
                        type: "warning",
                        title: data.statusText,
                        text: data.responseJSON.errors,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $(".overlay").addClass('hide');
                },
            });
        }
        $('.doaction').on('click', function() {
            var data_url = $(this).attr('data-url');

            var ids = '';
            $(".table").find('td :checked').each(function() {
                ids += $(this).val() + ',';
            });

            if (ids === '') {
                return;
            }
            do_post_action(data_url+ "&ids=" + ids.slice(0, -1))
        });


        var table = $('#table').dataTable({
            order: [
                [{{get_buzzy_config('p_multilanguage') == 'on' ? 6 : 5 }}, 'desc']
            ],
            processing: true,
            serverSide: true,
            autoWidth: false,
            language: {
                "sDecimal": ",",
                "infoEmpty": "{!! trans('admin.sEmptyTable')  !!}",
                "sInfo": "{!! trans('admin.sInfo')  !!}",
                "sInfoEmpty": "{!! trans('admin.sInfoEmpty')  !!}",
                "sInfoFiltered": "{!! trans('admin.sInfoFiltered')  !!}",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "{!! trans('admin.sLengthMenu')  !!}",
                "sLoadingRecords": "{!! trans('admin.sLoadingRecords')  !!}",
                "sProcessing": "{!! trans('admin.sProcessing')  !!}",
                "sSearch": "{!! trans('admin.sSearch')  !!}",
                "sZeroRecords": "{!! trans('admin.sZeroRecords')  !!}",
                "oPaginate": {
                    "sFirst": "{!! trans('admin.sFirst')  !!}",
                    "sLast": "{!! trans('admin.sLast')  !!}",
                    "sNext": "{!! trans('admin.sNext')  !!}",
                    "sPrevious": "{!! trans('admin.sPrevious')  !!}"
                },
                "oAria": {
                    "sSortAscending": "{!! trans('admin.sSortAscending')  !!}",
                    "sSortDescending": "{!! trans('admin.sSortDescending')  !!}"
                }
            },
            ajax: {
                "url": '{!! $data_url !!}',
                "data": function() {
                    setTimeout(function() {
                        BuzzyAdmin.init();
                    }, 2000);
                }
            },
            columns: [{
                    sType: 'html',
                    data: 'selection',
                    name: 'selection',
                    orderable: false,
                    searchable: false,
                    "width": "2%"
                },
                {
                    sType: 'html',
                    data: 'thumb',
                    name: 'thumb',
                    orderable: false,
                    searchable: false,
                    "width": "2%"
                },
                {
                    sType: 'html',
                    data: 'title',
                    name: 'title',
                    orderable: false,
                    searchable: true,
                    "width": "33%"
                },
                {
                    data: 'user',
                    name: 'user',
                    orderable: false,
                    searchable: false,
                    "width": "15%"
                },
                {
                    data: 'approve',
                    name: 'approve',
                    orderable: false,
                    searchable: false,
                    "width": "13%"
                },
                @if(get_buzzy_config('p_multilanguage') == 'on') {
                    data: 'language',
                    name: 'language',
                    orderable: true,
                    searchable: false,
                    "width": "10%"
                },
                @endif
                @if($type == 'features') {
                    data: 'featured_at',
                    name: 'featured_at',
                    orderable: true,
                    searchable: false,
                    "width": "10%"
                },
                @else {
                    data: 'published_at',
                    name: 'published_at',
                    orderable: true,
                    searchable: false,
                    "width": "10%"
                },
                @endif {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    "width": "10%"
                }
            ],
            drawCallback: function(settings) {
                $('.table input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                }).on('ifChecked', function(event) {
                    toggle_table_actions();
                }).on('ifUnchecked', function(event) {
                    toggle_table_actions();
                });
                $('.cho input[type="checkbox"]').iCheck({
                    checkboxClass: 'icheckbox_flat-blue',
                    radioClass: 'iradio_flat-blue'
                }).on('ifChecked', function(event) {
                    toggle_table_actions();
                    $(".table input[type='checkbox']").iCheck("check");
                    $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
                }).on('ifUnchecked', function(event) {
                    toggle_table_actions();
                    $(".table input[type='checkbox']").iCheck("uncheck");
                    $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
                });
                $('.do_post_action').on('click', function() {
                    var data_url = $(this).attr('data-url');

                    do_post_action(data_url);
                });
            }
        });

    });
</script>
@endsection
