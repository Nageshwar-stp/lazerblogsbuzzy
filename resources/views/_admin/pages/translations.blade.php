@extends("_admin.adminapp")

@section('header')
<style>
    .dd-placeholder {
        display: block;
        position: relative;
        margin: 0;
        padding: 0;
        min-height: 20px;
        font-size: 13px;
        line-height: 20px;
    }

    .dd-placeholder,
    .dd-empty {
        margin: 5px 0;
        padding: 0;
        min-height: 30px;
        background: #f2fbff;
        border: 1px dashed #b6bcbf;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .lang-actions {
        display: none;
    }

    .dd-item:hover .lang-actions {
        display: block;
    }
</style>
@endsection

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        {{ __('Translations') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> {{ trans('admin.dashboard') }}</a></li>
        <li class="active"> {{ __('Translations') }}</li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-3">

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __("Languages") }}</h3>
                    <div class="box-tools">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="dd box-body no-padding" id="nestlangs">
                    <ol class="dd-list nav nav-pills nav-stacked">
                        @foreach(\App\Language::active()->orderBy('order')->get() as $i => $lang)
                        <li class="dd-item {{ $locale==$lang->code ? 'active' : ""}}" data-order="{{$lang->order}}"
                            data-id="{{$lang->id}}">
                            <a href="/admin/translations/{{ $lang->code }}">
                                {{ trans($lang->name) }}
                                @if($lang->direction == 'rtl')
                                <span class="label label-success">RTL</span>
                                @endif
                                @if($lang->code == get_buzzy_config('sitedefaultlanguage', 'en'))
                                <span class="label label-danger">{{trans('admin.SiteLanguage')}}</span>
                                @endif
                                <div class="lang-actions pull-right">
                                    <div data-locale="{{ $lang->code }}"
                                        class="label label-primary language_lock pull-right"
                                        style="margin-left:4px;cursor:pointer" data-toggle="tooltip"
                                        data-original-title="{{trans('admin.Deactivate')}}">
                                        <i class="fa fa-lock"></i>
                                    </div>
                                    <div class="label label-warning dd-handle pull-right" data-toggle="tooltip"
                                        data-original-title="{{__('Order')}}">
                                        <i class="fa fa-arrows-alt"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ol>
                </div><!-- /.box-body -->
            </div><!-- /. box -->

            <div class="box box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ __("Disabled Languages") }}</h3>
                    <div class="box-tools">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding" id="nestlangs2">
                    <ul class="dd-list nav nav-pills nav-stacked">
                        @foreach(\App\Language::where('active', 0)->orderBy('code')->get() as $i => $lang)
                        <li class="dd-item {{ $locale==$lang->code ? 'active' : ""}}">
                            <a href="/admin/translations/{{ $lang->code }}">
                                {{ trans($lang->name) }}
                                @if($lang->direction == 'rtl')
                                <span class="label label-success">RTL</span>
                                @endif
                                <div class="lang-actions pull-right">
                                    <div data-locale="{{ $lang->code }}"
                                        class="label label-primary language_lock pull-right" style="cursor:pointer"
                                        data-toggle="tooltip" data-original-title="{{__('Activate')}}">
                                        <i class="fa fa-unlock"></i>
                                    </div>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div><!-- /.box-body -->
            </div><!-- /. box -->
        </div>
        <div class="col-md-9">

            <div class="box ">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-edit"></i>
                        <b>{{ trans('admin.edit') }} : {{ trans($current_language->name) }}</b>
                        @if($locale != 'en')<span style="color:gray">({{$trans_count}}/{{$total_count}})</span>@endif
                    </h3>
                    <div class="box-tools pull-right">
                        <a href="/admin/translations/{{$current_language->code}}/send"
                            class="btn btn-sm btn-success send_translation"
                            style="height: 25px;line-height:20px; padding: 2px 5px" data-toggle="tooltip"
                            data-placement="bottom"
                            data-original-title="{{__('Help the Community! If you fixed a word or if you have a better translation for a phrase feel free to share it with the community.')}}">
                            {{__('Send Translation')}}
                        </a>
                    </div>
                </div>
                <div class="box-body">

                    @if(count($translations))
                    <form action="{{url('/admin/translations/'.$locale)}}" method="post" class="post_form">
                        @csrf
                        <fieldset>
                            @foreach($translations as $slug => $translation)
                            <div class="form-group clearfix {{!$translation['is_translated'] ? 'has-warning' : ''}}">
                                <label class="control-label col-sm-4"
                                    for="{{$slug}}">{{ $translation['default'] }}</label>
                                <div class="controls col-sm-8">
                                    <input type="text" class="form-control" @if($current_language->direction ==
                                    'rtl')dir="rtl"@endif id="{{$slug}}" name="{{$slug}}"
                                    value="{{$translation['translation']}}">
                                </div> <!-- /controls -->
                            </div> <!-- /form-group -->
                            <hr />
                            @endforeach

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg"
                                    style="position: fixed; right: 50px; bottom: 50px">{{__('Save')}}</button>
                            </div> <!-- /form-actions -->
                        </fieldset>
                    </form>
                    @endif
                </div>
            </div>

        </div> <!-- /spa12 -->
    </div> <!-- /row -->
</section>

@endsection
@section('footer')
<script src="{{ asset('assets/plugins/adminlte/plugins/nestable/jquery.nestable.min.js') }}"></script>

<script>
    $('.language_lock').on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        var locale = $(this).data('locale');

        window.location = '/admin/translations/'+locale+'/lock';
    });


    $("#nestlangs").nestable({
        group: 1,
        maxDepth: 1,
        callback: function(l,e){
            var list   = l.length ? l : $(l.target);
            var langs = list.nestable('toArray');
            console.log(langs);
            $.ajax({
                url: '/admin/translations/sort',
                method: 'POST',
                responseType: 'json',
                data: {
                    'langs': langs,
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
