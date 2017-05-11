<!-- BEGIN FORM-->
{!! Form::model($getUserData,['route' => 'storeMingleSync', 'class' => 'ajax','id'=>'postadv', 'files' => true, 'method' =>'post' ])!!}
<!-- Modal Add report Start-->
<!--<div class="modal" id="addreport" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content clearfix">-->


<div class="form-horizontal mrg-top20">


    <div class="form-group">
        <label for="country" class="col-sm-4 control-label">{{trans('form.common.country')}}<span class="required">*</span></label>
        <div class="col-md-8">
            <div class="selectbox">
                {!! Form::select('country_id', (['' => 'Select Country']+ $countries),null,['class' => 'selectpicker select-country','id' => 'country', 'data-targetState'=>'select-state']) !!}
            </div>
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

    <div class="form-group">
        <label for="hobby" class="col-md-4 control-label">Hobbies<span class="required">*</span></label>
         {!! Form::select('hobbies[]', ($hobbies),null,['class' => 'selectpicker','id' => 'hobby','multiple'=>'multiple','data-selected-text-format'=>'count > 3','data-actions-box'=>true]) !!}
    </div>
    
    {!! Form::checkbox('agree_and_accept_terms_condition_and_privacy_policy', 1, ['checked']) !!}<span></span>
                                        <p>Agree that you have read and accept our <a target="_blank" href="{{route("generalTC")}}" class="accept-gtc-link"> General Terms &amp; Conditions</a> and  <a target="_blank" href="{{route("privacyPolicy")}}" class="accept-gtc-link">Privacy Policy</a></p>


</div>
<div class="form-btnblock clearfix text-right nomargin"> <a href="#" title="Cancel" class="cancel-link" data-dismiss="modal">Cancel</a>
    <input type="submit" title="Save" class="btn btn-primary" value="save">
</div>







<!--        </div>
    </div>
</div>-->
{!! Form::close() !!}
<!-- END FORM-->
<!--Modal Add report Close-->  