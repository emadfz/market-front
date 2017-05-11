<div class="registration-form clearfix reg-step3 bg-color" id="reg-step3"  style="display: none;">
    <h4>{{trans('form.auth.contact_info')}}</h4>
    <input type="hidden" value="step3" name="current_step"/>
    <div class="split-col">
        <div class="col-md-5 form-horizontal padd-leftnone">
            <div class="form-group">
                <label for="title" class="col-sm-4 control-label">{{ trans('form.common.title') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox width130">
                        {!! Form::select('title', $nameTitle, null, ['class'=>'selectpicker', 'id' => 'title']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="first_name" class="col-sm-4 control-label">{{ trans('form.auth.first_name') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'first_name', 'maxlength'=>20]) !!}
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class="col-sm-4 control-label">{{ trans('form.auth.last_name') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'last_name', 'maxlength'=>20]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for='gender' class="col-sm-4 control-label padd-topnone">{{ trans('form.auth.gender') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="width130">
                        {!! Form::select('gender', $gender, null, ['class'=>'selectpicker', 'id' => 'gender']) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="position" class="col-sm-4 control-label">{{ trans('form.auth.position') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox mrg-bott10">
                        {!! Form::select('position', $position, null, ['class'=>'selectpicker', 'id' => 'position']) !!}
                    </div>
                    <input type="text" class="form-control" name='other_position' />  
                </div>
            </div>

        </div>
        <div class="col-md-6 form-horizontal regstep-right">
            <div class="form-group">
                <label class="col-sm-4 control-label" for="date_of_birth">{{ trans('form.auth.date_of_birth') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="outer-field dateouter">
                        {!! Form::text('date_of_birth', null, ['class'=>'form-control datepicker-ui', 'maxDate'=>0, 'id' => 'date_of_birth']) !!}
                    </div> 
                </div>
            </div>
            <div class="form-group">
                <label for="phone_number" class="col-sm-4 control-label">{{ trans('form.auth.phone_number') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="outer-field">
                    {!! Form::text('country_code', null, ['class'=>'form-control width50 ccode', 'placeholder'=>'+11', 'id' => 'phone_number', 'maxlength'=>4]) !!}
                    {!! Form::text('phone_number', null, ['class'=>'form-control cphone-input', 'maxlength'=>16]) !!}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-4 control-label">{{ trans('form.common.email') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('email', null, ['class'=>'form-control', 'placeholder'=>'john@gmail.com', 'id' => 'email', 'maxlength'=>100]) !!}
                </div>
            </div>

            <div class="form-group">
                <label for="secret_question" class="col-sm-4 control-label">{{ trans('form.auth.secret_question') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    <div class="selectbox">
                        {!! Form::select('secret_question', $secretQuestionList, null, ['class'=>'selectpicker', 'id' => 'secret_question']) !!}
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="secret_answer" class="col-sm-4 control-label">{{ trans('form.auth.secret_answer') }}<span class="required">*</span></label>
                <div class="col-sm-8">
                    {!! Form::text('secret_answer', null, ['class'=>'form-control', 'id' => 'secret_answer', 'maxlength'=>100]) !!}
                </div>
            </div>

        </div>
    </div>
    <div class="form-btnblock clearfix text-right">
        <a href="javascript:;" class="btn btn-link business-reg-back-step" data-moveto="step2" title="{{trans('form.button.back')}}"><span class="backarrow"></span>{{trans('form.button.back')}}</a>
        <a href="{{route('homepage')}}" class="btn btn-link" title="{{trans('form.button.cancel')}}">{{trans('form.button.cancel')}}</a>
        <input type="submit" title="{{trans('form.button.next')}}" class="btn btn-primary" value="{{trans('form.button.next')}}" />
    </div>
</div>