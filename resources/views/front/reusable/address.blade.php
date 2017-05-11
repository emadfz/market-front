<div class="modal" id="addressModal" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            {!! Form::open(['route' => 'postAddress', 'class' => 'address-ajax', 'id' => 'addressForm', 'autocomplete' => 'off']) !!}
            <div class="modal-inner clearfix">
                <a href="#" class="close" data-dismiss="modal">close</a>
                <div class="forgot-modal">
                    <h6>forgot password</h6>
                    <p>Please enter your registered E-mail id, which you use as username.</p>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="forgot_email" class="col-sm-4 control-label">{{trans('form.common.email')}}<span class="required">*</span></label>
                            <div class="col-sm-8">
                            <!--<input type="email" class="form-control" id="fpemail" placeholder="">-->
                                {!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'forgot_email', 'maxlength'=>100, 'autofocus'=>'autofocus']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="forgot_date_of_birth" class="col-sm-4 control-label">{{ trans('form.auth.date_of_birth') }}<span class="required">*</span></label>
                            <div class="col-sm-8">
                                <div class="outer-field dateouter">
                                    {!! Form::text('date_of_birth', null, ['class'=>'form-control datepicker-ui', 'maxDate'=>0, 'id' => 'forgot_date_of_birth']) !!}
                                </div>  
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="forgot_secret_question" class="col-sm-4 control-label">{{ trans('form.auth.secret_question') }}<span class="required">*</span></label>
                            <div class="col-sm-8">
                                {!! Form::select('secret_question', , null, ['class'=>'selectpicker', 'id' => 'forgot_secret_question']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="forgot_secret_answer" class="col-sm-4 control-label">{{ trans('form.auth.secret_answer') }}<span class="required">*</span></label>
                            <div class="col-sm-8">
                                {!! Form::text('secret_answer', null, ['class'=>'form-control', 'id' => 'forgot_secret_answer']) !!}
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <label class="col-sm-4 control-label">Captcha<span class="required">*</span></label>
                            <div class="col-sm-8">
                                {--!! app('captcha')->display(); !!--}
                            </div>
                        </div>-->

                        <div class="form-group forgot_captcha_failed_attempt" id="forgot_captcha_parent_div_id" style="display: none;">
                            <label class="col-sm-4 control-label">Captcha<span class="required">*</span></label>
                            <div class="col-sm-8" id="forgot_captcha_child_div_html"></div>
                        </div>

                    </div>
                    <p class="small-semibold" style="padding-top: 15px;">if you are experiencing problems with remembering you username or email. please send us feedback or email us at <a href="mailto:help@inspree.com" title="help@inspree.com">help@inspree.com</a></p>
                    <div class="form-btnblock clearfix text-right">
                        <a href="#signin" title="Cancel" class="btn btn-link" data-toggle="modal" data-dismiss="modal">Cancel</a>
                        <input type="submit" title="Submit" class="btn btn-primary" value="submit">
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>