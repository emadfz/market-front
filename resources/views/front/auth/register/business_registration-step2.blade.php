<div class="registration-form clearfix reg-step2 bg-color" id="reg-step2" style="display: none;">
    <h4>{{trans('form.auth.address_info')}}</h4>
    <input type="hidden" value="step2" name="current_step"/>
    <div class="split-col">
        <div class="col-md-5 form-horizontal padd-leftnone" id="syncWithBilling">
            <h4>{{trans('form.auth.billing_address')}}</h4>
            <div class="form-group">
                <label for="billing_address_1" class="col-sm-4 control-label">{{trans('form.auth.address_1')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('billing_address_1', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'billing_address_1', 'maxlength'=>100]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="billing_address_2" class="col-sm-4 control-label">{{trans('form.auth.address_2')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('billing_address_2', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'billing_address_2', 'maxlength'=>100]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="billing_country" class="col-sm-4 control-label">{{trans('form.common.country')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox">
                        {!! Form::select('billing_country', (['' => 'Select Country']+ $countries),null,['class' => 'selectpicker select-country','id' => 'billing_country', 'data-targetState'=>'select-billing-state']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="billing_postal_code" class="col-sm-4 control-label">{{trans('form.common.postal_code')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('billing_postal_code', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'billing_postal_code', 'maxlength'=>10]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="billing_state" class="col-sm-4 control-label">{{trans('form.common.state')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox">
                        {!! Form::select('billing_state', (['' => 'Select State']),null,['class' => 'selectpicker select-state select-billing-state','id' => 'billing_state', 'data-targetCity'=>'select-billing-city']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="billing_city" class="col-sm-4 control-label">{{trans('form.common.city')}}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox">
                        {!! Form::select('billing_city', (['' => 'Select City']),null,['class' => 'selectpicker select-billing-city','id'=>'billing_city']) !!}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-6 form-horizontal regstep-right">
            <h4>{{trans('form.auth.shipping_address')}}</h4>
            <div class="form-group">
                <div class="col-sm-12 device-center">
                    <span class="note-text">{{trans('form.auth.shipping_info_same_as_billing_info')}}</span>
                    <div class="custom-radio">
                        <label for="infoyes"><input id="infoyes" type="radio" name="same_address_info" value="yes" /><span></span>Yes</label>
                        <label for="infono"><input id="infono" type="radio" name="same_address_info" value="no" checked /><span></span>No</label>
                    </div>
                </div>
            </div>
            <div class="biz-shipping-info-disable-enable">
                <div class="form-group">
                    <label for="shipping_address_1" class="col-sm-4 control-label">{{trans('form.auth.address_1')}}<span class="required">*</span></label>
                    <div class="col-sm-8">
                        {!! Form::text('shipping_address_1', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'shipping_address_1', 'maxlength'=>100]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_address_2" class="col-sm-4 control-label">{{trans('form.auth.address_2')}}<span class="required">*</span></label>
                    <div class="col-sm-8">
                        {!! Form::text('shipping_address_2', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'shipping_address_2', 'maxlength'=>100]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_country" class="col-sm-4 control-label">{{trans('form.common.country')}}<span class="required">*</span></label>
                    <div class="col-sm-8">
                        <div class="selectbox">
                            {!! Form::select('shipping_country', (['' => 'Select Country']+ $countries),null,['class' => 'selectpicker select-country','id' => 'shipping_country', 'data-targetState'=>'select-shipping-state']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_postal_code" class="col-sm-4 control-label">{{trans('form.common.postal_code')}}<span class="required">*</span></label>
                    <div class="col-sm-8">
                        {!! Form::text('shipping_postal_code', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'shipping_postal_code', 'maxlength'=>10]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="shipping_state" class="col-sm-4 control-label">{{trans('form.common.state')}}<span class="required">*</span></label>
                    <div class="col-sm-8">
                        <div class="selectbox">
                            {!! Form::select('shipping_state', (['' => 'Select State']),null,['class' => 'selectpicker select-state select-shipping-state','id' => 'shipping_state', 'data-targetCity'=>'select-shipping-city']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="shipping_city" class="col-sm-4 control-label">{{trans('form.common.city')}}<span class="required">*</span></label>
                    <div class="col-sm-8">
                        <div class="selectbox">
                            {!! Form::select('shipping_city', (['' => 'Select City']),null,['class' => 'selectpicker select-shipping-city','id'=>'shipping_city']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-btnblock clearfix text-right">
        <a href="javascript:;" class="btn btn-link business-reg-back-step" data-moveto="step1" title="{{trans('form.button.back')}}" /><span class="backarrow"></span>{{trans('form.button.back')}}</a>
        <a href="{{route('homepage')}}" class="btn btn-link" title="{{trans('form.button.cancel')}}">{{trans('form.button.cancel')}}</a>
        <input type="submit" title="{{trans('form.button.next')}}" class="btn btn-primary" value="{{trans('form.button.next')}}" />
    </div>
</div>