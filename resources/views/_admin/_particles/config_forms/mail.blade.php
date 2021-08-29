<div class="row">
    <div class="col-sm-12  col-md-8 col-lg-6">
        <div class="panel panel-primary">
            <div class="panel-heading" style="display: flex; align-items:center;">
                {{ trans('admin.MailSettings') }}
                <a href="https://support.akbilisim.com/docs/buzzy/mail-configuration" target="_blank"
                    style="margin-left:auto;color:#fff!important;" class="btn btn-sm btn-success"><i
                        class="fa fa-eye"></i> @lang('v4.see_here_more_info')</a><br>
                <a href="/admin/test-mail-config" data-toggle="tooltip"
                    data-original-title="{{trans('v4.send_test_email_info', ['email' => auth()->user()->email])}}"
                    style="color:#fff!important;" class="btn btn-sm btn-warning"><i class="fa fa-eye"></i>
                    @lang('v4.send_test_email')</a><br>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label"> MAIL DRIVER</label>
                    <div class="controls">
                        {!! Form::select('MAIL_DRIVER', [
                        'smtp' => 'SMTP',
                        'ses' => 'Ses (Amazon Simple Email Service)',
                        'mailgun' => 'Mailgun',
                        'mail' => 'PHP Mail',
                        'sendmail' => 'SendMail',
                        'log' => __('Log (Email will be saved to error log)')
                        ],
                        env('MAIL_DRIVER', 'log'), ['data-dependecy' => 'mail_driver_input', 'class' => 'form-control'])
                        !!}
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="smtp">
                    <label class="control-label"> MAIL HOST</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" placeholder="smtp.gmail.com" name="MAIL_HOST"
                            value="{{  env('MAIL_HOST') }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="smtp">
                    <label class="control-label"> MAIL PORT</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" placeholder="587" name="MAIL_PORT"
                            value="{{  env('MAIL_PORT') }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="smtp">
                    <label class="control-label"> MAIL USERNAME</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="MAIL_USERNAME"
                            value="{{   auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" : env('MAIL_USERNAME')  }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="smtp">
                    <label class="control-label"> MAIL PASSWORD</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="MAIL_PASSWORD"
                            value="{{  auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" : env('MAIL_PASSWORD')   }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="smtp">
                    <label class="control-label"> MAIL ENCRYPTION</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" placeholder="tls" name="MAIL_ENCRYPTION"
                            value="{{  env('MAIL_ENCRYPTION') }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="ses">
                    <label class="control-label">SES ACCESS KEY ID</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="SES_ACCESS_KEY_ID"
                            value="{{  auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" :  env('SES_ACCESS_KEY_ID') }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="ses">
                    <label class="control-label">SES SECRET ACCESS KEY</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="SES_SECRET_ACCESS_KEY"
                            value="{{  auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" :  env('SES_SECRET_ACCESS_KEY') }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="ses">
                    <label class="control-label">SES DEFAULT REGION</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="SES_DEFAULT_REGION"
                            value="{{  auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" :  env('SES_DEFAULT_REGION') }}">
                    </div>
                </div>

                <div class="form-group" data-target="mail_driver_input" data-value="mailgun">
                    <label class="control-label">MAILGUN DOMAIN</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="MAILGUN_DOMAIN"
                            value="{{  env('MAILGUN_DOMAIN') }}">
                    </div>
                </div>
                <div class="form-group" data-target="mail_driver_input" data-value="mailgun">
                    <label class="control-label">MAILGUN SECRET</label>
                    <div class="controls">
                        <input type="text" class="form-control input-lg" name="MAILGUN_SECRET"
                            value="{{  auth()->user()->email == 'demo@admin.com' ?  "-YOU DON'T HAVE PERMISSION TO SEE THAT-" :  env('MAILGUN_SECRET') }}">
                    </div>
                </div>

                <hr>
                <div class="form-group">
                    <label class="control-label">MAIL FROM NAME</label>
                    <input type="text" class="form-control input-lg" name="BuzzyContactName"
                        value="{{  get_buzzy_config('BuzzyContactName') }}"
                        placeholder="{{get_buzzy_config('siteemail')}}">
                </div>
                <div class="form-group">
                    <label class="control-label">MAIL FROM ADDRESS</label>
                    <input type="text" class="form-control input-lg" name="BuzzyContactEmail"
                        value="{{  get_buzzy_config('BuzzyContactEmail') }}"
                        placeholder="{{get_buzzy_config('sitename')}}">
                </div>
            </div>
        </div><!-- /.panel -->
    </div><!-- /.col -->
</div><!-- /.row -->
