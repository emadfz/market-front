<!-- BEGIN FORM-->
{!! Form::open(['route' => 'profileChangePassword', 'class' => 'closeModalAfterReportFlag ajax','id'=>'postadv', 'files' => true ])!!}
<!-- Modal Add report Start-->
<!--<div class="modal" id="addreport" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content clearfix">-->
                <a href="#" class="close" data-dismiss="modal">{{trans('profile.label.close')}}</a>
      		<h6>{{trans('profile.label.change_password')}}</h6>
       		<div class="form-horizontal mrg-top20">
                    {!! Form::hidden('user_id', @$userId, ['class' => 'user_id','id'=>'user_id']) !!}   
                    <div class="form-group">
                        <label for="current_password" class="col-sm-4 control-label">{{trans('profile.label.currentpass')}}<span class="required">*</span></label>
                        <div class="col-sm-8">
                        {!! Form::input('password','current_password', null, ['class'=>'form-control','id' => 'currentpassword','maxlength'=>100, 'autocomplete'=>'off']) !!}
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label for="new_password" class="col-sm-4 control-label">{{trans('profile.label.newpass')}}<span class="required">*</span></label>
                        <div class="col-sm-8">
                        {!! Form::input('password','new_password', null, ['class'=>'form-control','id' => 'newpassword','maxlength'=>14, 'autocomplete'=>'off']) !!}
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label for="confirm_new_password" class="col-sm-4 control-label">{{trans('profile.label.confirmnewpass')}}<span class="required">*</span></label>
                        <div class="col-sm-8">
                        {!! Form::input('password','confirm_new_password', null, ['class'=>'form-control','id' => 'confirmnew_password','maxlength'=>14, 'autocomplete'=>'off']) !!}
                        </div>
                    </div>
                
            </div>
            <div class="form-btnblock clearfix text-right nomargin">
                <a href="#" title="Cancel" class="cancel-link" data-dismiss="modal">Cancel</a>
            	<input type="submit" title="Submit" class="btn btn-primary" value="submit">
            </div>
    
      
            
            
<!--        </div>
    </div>
</div>-->
{!! Form::close() !!}
<!-- END FORM-->
<!--Modal Add report Close-->  