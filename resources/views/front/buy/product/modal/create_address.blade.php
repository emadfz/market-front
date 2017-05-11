<?php
$productOriginAddressData = ($updateFlag == TRUE && !empty($productData['productOriginAddress'])) ? $productData['productOriginAddress']:[];
$productStates = (!empty($productOriginAddressData)) ? getAllStates($productOriginAddressData['country_id'], TRUE) : [];
$productCities = (!empty($productOriginAddressData)) ? getAllCities($productOriginAddressData['state_id'], TRUE) : [];
?>
<div id="productOriginAddressId" style="display: none;">
    <input type="hidden" value="{{(!empty(@$productOriginAddressData)) ? encrypt($productOriginAddressData['id']) : ''}}" name="user_address_id">
    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="form-group">
                {!! Form::text('billing_address_1', (!empty(@$productOriginAddressData)) ? $productOriginAddressData['address_1'] : null, ['class'=>'form-control', 'placeholder'=>trans('form.auth.address_1'), 'id' => 'billing_address_1', 'maxlength'=>100]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-2">
            <div class="form-group">
                {!! Form::text('billing_address_2', (!empty(@$productOriginAddressData)) ? $productOriginAddressData['address_2'] : null, ['class'=>'form-control', 'placeholder'=>trans('form.auth.address_2'), 'id' => 'billing_address_2', 'maxlength'=>100]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-horizontal col-md-10 col-md-offset-2">
            <div class="form-group">
                <label for="billing_postal_code" class="control-label col-md-2 padd-topnone">{{trans('form.common.postal_code')}}<span class="required">*</span></label>
                <div class="selectbox col-md-4">
                    {!! Form::text('billing_postal_code', (!empty(@$productOriginAddressData)) ? $productOriginAddressData['postal_code'] : null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'billing_postal_code', 'maxlength'=>10]) !!}
                </div>
                
                <label for="billing_country" class="control-label col-md-2">{{trans('form.common.country')}}<span class="required">*</span></label>
                <div class="selectbox col-md-4">
                    {!! Form::select('billing_country', (['' => 'Select Country']+ $countries),(!empty(@$productOriginAddressData)) ? $productOriginAddressData['country_id'] : null,['class' => 'selectpicker select-country','id' => 'billing_country', 'data-targetState'=>'select-billing-state']) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-horizontal col-md-10 col-md-offset-2">
            <div class="form-group">
                <label for="billing_state" class="control-label col-md-2 padd-topnone">{{trans('form.common.state')}}<span class="required">*</span></label>
                <div class="selectbox col-md-4">
                    {!! Form::select('billing_state', (['' => 'Select State']+$productStates) ,(!empty(@$productOriginAddressData)) ? $productOriginAddressData['state_id'] : null,['class' => 'selectpicker select-state select-billing-state','id' => 'billing_state', 'data-targetCity'=>'select-billing-city']) !!}
                </div>
                <label for="billing_city" class="control-label col-md-2">{{trans('form.common.city')}}<span class="required">*</span></label>
                <div class="selectbox col-md-4">
                    {!! Form::select('billing_city', (['' => 'Select City']+$productCities),(!empty(@$productOriginAddressData)) ? $productOriginAddressData['city_id'] : null,['class' => 'selectpicker select-billing-city','id'=>'billing_city']) !!}
                </div>
            </div>
        </div>
    </div>
</div>