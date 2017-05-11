<div class="registration-form clearfix reg-step4 bg-color" id="reg-step4"  style="display: none;">
    <h4>{{trans('form.auth.payment_info')}}</h4>
    <input type="hidden" value="step4" name="current_step"/>
    <div class="split-col">
        <div class="col-md-5 form-horizontal padd-leftnone">
            <h4>{{trans('form.auth.credit_debit_card_details')}}</h4>
            <div class="form-group">
                <label for="card_full_name" class="col-sm-4 control-label">{{trans('form.auth.full_name')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('full_name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'card_full_name', 'maxlength'=>100]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="pay_card_type" class="col-sm-4 control-label">{{trans('form.auth.card_type')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox">
                        {!! Form::select('card_type', $cardTypes, null, ['class'=>'selectpicker', 'id' => 'pay_card_type']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="card_number" class="col-sm-4 control-label">{{trans('form.auth.card_number')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('card_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'card_number', 'maxlength'=>16]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="card_expiry_month" class="col-sm-4 control-label">{{trans('form.auth.expiry_date')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox cardexpire width100">
                        {!! Form::select('expiry_month', $expiryMonths, null, ['class'=>'selectpicker', 'id' => 'card_expiry_month']) !!}
                    </div>
                    <div class="selectbox cardexpire width100">
                        {!! Form::select('expiry_year', $expiryYears, null, ['class'=>'selectpicker', 'id' => 'card_expiry_year']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 form-horizontal regstep-right">
            <h4>{{trans('form.auth.bank_info')}}</h4>
            <div class="form-group">
                <div class="col-sm-12">
                    <p>{{trans('form.auth.bank_info_tagline')}}</p>
                </div>
            </div>
            <div class="form-group">
                <label for="pay_bankname" class="col-sm-4 control-label">{{trans('form.auth.bank_name')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('bank_name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'pay_bankname', 'maxlength'=>100]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="pay_phoneno" class="col-sm-4 control-label">{{trans('form.auth.phone_number')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('bank_phone_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'pay_phoneno', 'maxlength'=>20]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="pay_routing_number" class="col-sm-4 control-label">{{trans('form.auth.routing_number')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('bank_routing_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'pay_routing_number', 'maxlength'=>25]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="pay_accno" class="col-sm-4 control-label">{{trans('form.auth.account_number')}}</label>
                <div class="col-sm-8">
                    {!! Form::text('bank_account_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'pay_accno', 'maxlength'=>25]) !!}
                </div>
            </div>

        </div>
    </div>
    <div class="col-sm-10 col-xs-12 col-md-offset-2">
        <div class="termschecks">
        <div class="custom-checkbox">
            <label>
                {!! Form::checkbox('is_subscribed', 1) !!}<span></span> 
                <p>{{ trans('form.auth.send_me_newsletter_by_email_sms') }}</p>
            </label>
            <label>
                {!! Form::checkbox('agree_and_accept_terms_condition_and_privacy_policy', 1, ['checked']) !!}<span></span>
                <p>Agree that you have read and accept our <a target="_blank" href="{{route("generalTC")}}" class="accept-gtc-link"> General Terms &amp; Conditions</a> and  <a target="_blank" href="{{route("privacyPolicy")}}" class="accept-gtc-link">Privacy Policy</a></p>
            </label>

        </div>
            </div>
    </div>
    <div class="form-btnblock clearfix text-right">
        <a href="javascript:;" class="btn btn-link business-reg-back-step" data-moveto="step3" title="{{trans('form.button.back')}}"><span class="backarrow"></span>{{trans('form.button.back')}}</a>
        <a href="{{route('homepage')}}" class="btn btn-link" title="{{trans('form.button.cancel')}}">{{trans('form.button.cancel')}}</a>
        <input type="submit" title="{{trans('form.button.register')}}" class="btn btn-primary" value="{{trans('form.button.register')}}" />
    </div>
</div>