<!-- BEGIN FORM-->
{!! Form::model($getUserData,['route' => 'storeEditAddress', 'class' => 'ajax','id'=>'postadv', 'files' => true, 'method' =>'post' ])!!}
<!-- Modal Add report Start-->
<!--<div class="modal" id="addreport" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content clearfix">-->
<div class="modal-inner clearfix"> 
    <a href="#" class="close" data-dismiss="modal">{{trans('profile.label.close')}}</a>
    <h6>{{trans('profile.label.add_edit_address')}}</h6>
    <div class="form-horizontal mrg-top20">
        <div class="form-group">
            <label for="address_1" class="col-sm-4 control-label">{{trans('form.auth.address_1')}}<span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::Hidden('Id', @$Id) !!}
                {!! Form::text('address_1', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'address_1', 'maxlength'=>100]) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="address_2" class="col-sm-4 control-label">{{trans('form.auth.address_2')}}<span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('address_2', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'address_2', 'maxlength'=>100]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="country" class="col-sm-4 control-label">{{trans('form.common.country')}}<span class="required">*</span></label>
            <div class="col-md-8">
                <div class="selectbox">
                    {!! Form::select('country_id', (['' => 'Select Country']+ $countries),null,['class' => 'selectpicker select-country','id' => 'country', 'data-targetState'=>'select-state']) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="postal_code" class="col-md-4 control-label">{{trans('form.common.postal_code')}}<span class="required">*</span></label>
            <div class="col-sm-8">
                {!! Form::text('postal_code', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'postal_code', 'maxlength'=>10]) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="state" class="col-md-4 control-label">{{trans('form.common.state')}}<span class="required">*</span></label>
            <div class="col-sm-8">
                <div class="selectbox">
                    @if(!empty($Id))
                    {!! Form::select('state_id', getAllStates(@$getUserData['country_id'], TRUE),@$getUserData['state_id'],['class' => 'selectpicker select-state','id' => 'state', 'data-targetCity'=>'select-city']) !!}
                    @else
                    {!! Form::select('state_id', (['' => 'Select State']),null,['class' => 'selectpicker select-state','id' => 'state', 'data-targetCity'=>'select-city']) !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="city" class="col-md-4 control-label">{{trans('form.common.city')}}<span class="required">*</span></label>
            <div class="col-sm-8">
                <div class="selectbox">
                    @if(!empty($Id))
                    {!! Form::select('city_id', getAllCities(@$getUserData['state_id'], TRUE),@$getUserData['city_id'],['class' => 'selectpicker select-city','id'=>'city']) !!}
                    @else
                    {!! Form::select('city_id',(['' => 'Select City']),null,['class' => 'selectpicker select-city','id'=>'city']) !!}
                    @endif
                </div>
            </div>
        </div>


    </div>
    <div class="form-btnblock clearfix text-right nomargin"> <a href="#" title="Cancel" class="cancel-link" data-dismiss="modal">Cancel</a>
        <input type="submit" title="Save" class="btn btn-primary" value="save">
    </div>
</div>







<!--        </div>
    </div>
</div>-->
{!! Form::close() !!}
<!-- END FORM-->
<!--Modal Add report Close-->  