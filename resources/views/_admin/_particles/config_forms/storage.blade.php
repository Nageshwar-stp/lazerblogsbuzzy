<div class="row">
    <div class="col-sm-12  col-md-8 col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading">{{ trans('v3.file_storage_settings') }}
                <a href="http://buzzy.akbilisim.com/admin/docs#AWSS3" target="_blank"
                    style="margin-top:-5px;color:#fff!important;" class="btn btn-sm btn-success pull-right"><i
                        class="fa fa-eye"></i> See here for more info</a><br>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label">{{ __('Maximum File Upload Size') }}</label>
                    <div class="controls">
                        <input type="number" class="form-control input-lg" name="user_max_fileupload_size"
                            value="{{  get_buzzy_config('user_max_fileupload_size') }}" placeholder="2000">
                    </div>
                    <span
                        class="help-block">{{ __('Maximum size for a single file user can upload. Default: 2000=2MB') }}</span>
                </div>
                <hr>
                <div class="form-group">
                    <label>{{ trans('v3.activate_s3') }}</label>
                    {!! Form::select('FILESYSTEM_DRIVER', ['local' => trans('admin.no'), 's3' => trans('admin.yes')],
                    env('FILESYSTEM_DRIVER'), ['class' => 'form-control']) !!}
                </div>
                <div class="form-group">
                    <label class="control-label"> AWS ACCESS KEY ID</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="AWS_ACCESS_KEY_ID"
                            value="{{ env('APP_DEMO') && auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" : env('AWS_ACCESS_KEY_ID')  }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"> AWS SECRET ACCESS KEY</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="AWS_SECRET_ACCESS_KEY"
                            value="{{ env('APP_DEMO') && auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" : env('AWS_SECRET_ACCESS_KEY')  }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"> AWS REGION</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="AWS_DEFAULT_REGION"
                            value="{{  env('AWS_DEFAULT_REGION') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"> AWS BUCKET</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="AWS_BUCKET"
                            value="{{  env('AWS_BUCKET')  }}">
                    </div>
                </div>

            </div>
        </div>

    </div><!-- /.col -->

</div><!-- /.row -->
