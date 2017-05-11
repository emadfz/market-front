<div class="registration-form clearfix reg-step bg-color"  id="reg-step1">
    <h4>{{trans('form.auth.company_info')}}</h4>
    <input type="hidden" value="step1" name="current_step"/>
    <div class="split-col">
        <div class="col-md-5 form-horizontal">
            <div class="form-group">
                <label for="username" class="col-sm-4 control-label">{{ trans('form.auth.username') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'username', 'maxlength'=>25, 'autofocus'=>'autofocus']) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="password_strength" class="col-sm-4 control-label">{{ trans('form.auth.password') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="outer-field">
                        {!! Form::password('password', ['class'=>'form-control padd-right35', 'id' => 'password_strength', 'maxlength'=>14, 'autocomplete'=>'off']) !!}
                        <span class="password-view">view</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="col-sm-4 control-label">{{ trans('form.auth.confirm_password') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="outer-field">
                        {!! Form::password('confirm_password', ['class'=>'form-control padd-right35', 'id' => 'confirm_password', 'maxlength'=>14, 'autocomplete'=>'off']) !!}
                        <span class="password-view">view</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="business_name" class="col-sm-4 control-label">{{trans('form.auth.business_name')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('business_name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_name', 'maxlength'=>50]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="industry_type" class="col-sm-4 control-label">{{trans('form.auth.industry_type')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox">
                        {!! Form::select('industry_type', $industryTypes, null, ['class'=>'selectpicker', 'id' => 'industry_type']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="business_details" class="col-sm-4 control-label">{{trans('form.auth.business_details')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::textarea('business_details', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_details', 'maxlength'=>500, 'rows'=>'4', 'cols'=>'50']) !!}
                </div>
            </div>
        </div>
        <div class="col-md-5 form-horizontal regstep-right">
            <div class="form-group">
                <label for="tax_id_number" class="col-sm-4 control-label">{{trans('form.auth.tax_id_no')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('tax_id_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'tax_id_number', 'maxlength'=>50]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="business_reg_number" class="col-sm-4 control-label">{{trans('form.auth.business_reg_no')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('business_reg_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_reg_number', 'maxlength'=>50]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="business_phone_number" class="col-sm-4 control-label">{{trans('form.auth.business_phone')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('business_phone_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_phone_number', 'maxlength'=>20]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="website" class="col-sm-4 control-label">{{trans('form.common.website')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('website', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'website', 'maxlength'=>100]) !!}
                </div>
            </div>
        </div>
    </div>
    <div class="form-btnblock clearfix text-right">
        <a href="{{route('homepage')}}" class="btn btn-link" title="{{trans('form.button.cancel')}}">{{trans('form.button.cancel')}}</a>
        <input type="submit" title="{{trans('form.button.next')}}" class="btn btn-primary" value="{{trans('form.button.next')}}" />
    </div>
</div>