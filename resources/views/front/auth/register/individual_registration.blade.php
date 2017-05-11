@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li class="active">{{ trans('form.auth.individual_registration') }}</li>
                </ul>
            </div>
        </div>
        <h2>{{ trans('form.auth.individual_registration') }}</h2>
        <div class="widecolumn registration-personal flexbox clearfix">

            <div class="col-md-6 bg-color clearfix">
                {!! Form::open(['route' => 'individualRegister', 'class' => 'ajax', 'id' => 'individual-register-form']) !!}
                <h5>Take advantage of the features and benefits that make learn about <span>Membership</span> Benefits</h5>
                <div class="form-horizontal individual-register">
                    <div class="form-group">
                        <label for="username" class="col-sm-4 control-label">{{ trans('form.auth.username') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('username', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'username', 'maxlength'=>25, 'autofocus'=>'autofocus']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-4 control-label">{{ trans('form.common.email') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('email', session('socialiteData.email'), ['class'=>'form-control', 'placeholder'=>'', 'id' => 'email', 'maxlength'=>100]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="title" class="col-sm-4 control-label">{{ trans('form.common.title') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <div class="width130">
                                {!! Form::select('title', $nameTitle, null, ['class'=>'selectpicker', 'id' => 'title']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="first_name" class="col-sm-4 control-label">{{ trans('form.auth.first_name') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('first_name', session('socialiteData.first_name'), ['class'=>'form-control', 'placeholder'=>'', 'id' => 'first_name', 'maxlength'=>20]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="last_name" class="col-sm-4 control-label">{{ trans('form.auth.last_name') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('last_name', session('socialiteData.last_name'), ['class'=>'form-control', 'placeholder'=>'', 'id' => 'last_name', 'maxlength'=>20]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class="col-sm-4 control-label">{{ trans('form.auth.gender') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <div class="width130">
                                {!! Form::select('gender', $gender, @session('socialiteData.gender')?ucfirst(session('socialiteData.gender')):null, ['class'=>'selectpicker', 'id' => 'gender']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="date_of_birth">{{ trans('form.auth.date_of_birth') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <div class="outer-field dateouter">
                                {!! Form::text('date_of_birth', null, ['class'=>'form-control datepicker-ui', 'maxDate'=>0, 'id' => 'date_of_birth']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label" for="phone_number">{{ trans('form.auth.phone_number') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <div class="outer-field">
                                {!! Form::text('country_code', null, ['class'=>'form-control width50 ccode', 'placeholder'=>'+11', 'id' => 'phone_number', 'maxlength'=>4]) !!}
                                {!! Form::text('phone_number', null, ['class'=>'form-control cphone-input', 'maxlength'=>16]) !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="secret_question" class="col-sm-4 control-label">{{ trans('form.auth.secret_question') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::select('secret_question', $secretQuestionList, null, ['class'=>'selectpicker', 'id' => 'secret_question']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="secret_answer" class="col-sm-4 control-label">{{ trans('form.auth.secret_answer') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            {!! Form::text('secret_answer', null, ['class'=>'form-control', 'id' => 'secret_answer', 'maxlength'=>100]) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_strength" class="col-sm-4 control-label">{{ trans('form.auth.password') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <div class="outer-field">
                                {!! Form::password('password', ['class'=>'form-control padd-right35', 'id' => 'password_strength', 'maxlength'=>14, 'autocomplete'=>'off']) !!}
                                <span class="password-view">view</span>
                            </div>
<!--                            <div class="progress">
                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width:80%"></div>
                            </div>
                            <p class="small-semibold">Password Strength Good</p>
                            <p class="infotext">Passwords are case sensitive. Atlas 7 characters. No space. must include both letters and numbers. No more than 2 consecutive repeating characters</p>-->
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="confirm_password" class="col-sm-4 control-label">{{ trans('form.auth.confirm_password') }}<span class="required">*</span></label>
                        <div class="col-sm-8">
                            <div class="outer-field">
                                {!! Form::password('confirm_password', ['class'=>'form-control padd-right35', 'id' => 'confirm_password', 'maxlength'=>14, 'autocomplete'=>'off']) !!}
                                <span class="password-view">view</span>
                            </div>
                            <div class="indi-termschecks">
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
                    </div>
                    {!!  Form::hidden('social_id', session('socialiteData.social_id')) !!}
                    {!!  Form::hidden('provider', session('socialiteData.provider')) !!}
                    <div class="form-btnblock clearfix text-right">
                        {!! Form::submit(trans('form.button.register'), ['class' => 'btn btn-primary', 'title' => trans('form.button.register')]) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-md-6 signin-option clearfix">
                <h3>{{trans('form.auth.already_a_user')}}</h3>
                <div class="form-group">
                    <a href="javascript:void(0)" title="{{trans('form.auth.signin')}}" data-toggle="modal" data-backdrop="static" data-keyboard="false" class="btn btn-primary signinModal">{{trans('form.auth.signin')}}</a>
                </div>
                <div class="or clearfix"><span>{{trans('form.auth.or')}}</span></div>
                <ul class="signin-social clearfix">
                    <li><a href="{{ route('socialAuthRedirectProvider', ['facebook']) }}" class="facebook" title="{{trans('form.auth.signin_facebook')}}"><span></span>{{trans('form.auth.signin_facebook')}}</a></li>
                    <li><a href="{{ route('socialAuthRedirectProvider', ['twitter']) }}" class="twitter" title="{{trans('form.auth.signin_twitter')}}"><span></span>{{trans('form.auth.signin_twitter')}}</a></li>
                    <li><a href="{{ route('socialAuthRedirectProvider', ['google']) }}" class="google" title="{{trans('form.auth.signin_google')}}"><span></span>{{trans('form.auth.signin_google')}}</a></li>
                    <li><a href="{{ route('socialAuthRedirectProvider', ['linkedin']) }}" class="linkedin" title="{{trans('form.auth.signin_linkedin')}}"><span></span>{{trans('form.auth.signin_linkedin')}}</a></li>
                </ul>

                <div class="benefit-join">
                    <h3>{{trans('form.auth.do_you_want_sell_product')}}</h3>
                    <p class="benefit-join">Benefits to join</p>
                    <ol>
                        <li>There are many variations of passages of Lorem Ipsum available,</li> 
                        <li>Contrary to popular belief, Lorem Ipsum is not simply random.</li> 
                    </ol>
                </div>
                <a href="{{ route('businessRegister') }}" class="btn btn-link ba-btn" style="float: none;" title="{{trans('form.auth.register_for_business_account')}}">{{trans('form.auth.register_for_business_account')}}</a>
            </div>

        </div>
    </div>
</section>
@endsection