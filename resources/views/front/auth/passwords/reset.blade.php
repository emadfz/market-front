@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li class="active">{{ trans('form.auth.reset_password') }}</li>
                </ul>
            </div>
        </div>
        <h2>{{ trans('form.auth.reset_password') }}</h2>
        <div class="widecolumn registration-personal flexbox clearfix">
            <div class="col-md-12 bg-color clearfix">

                {!! Form::open(['route' => 'postResetPassword', 'class' => 'ajax form-horizontal', 'id' => 'reset-password-form']) !!}
                <div class="form-group">
                    <label for="email" class="col-md-4 control-label">{{ trans('form.common.email') }}</label>
                    <div class="col-md-6">
                        {!! Form::text('email', @$userInfo['email'], ['class'=>'form-control', 'placeholder'=>'', 'id' => 'email', 'maxlength'=>100, 'readonly'=>true]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_strength" class="col-md-4 control-label">{{ trans('form.auth.password') }}<span class="required">*</span></label>
                    <div class="col-md-6">
                        <div class="outer-field">
                            {!! Form::password('password', ['class'=>'form-control padd-right35', 'id' => 'password_strength', 'maxlength'=>14, 'autocomplete'=>'off', 'autofocus'=>'autofocus']) !!}
                            <span class="password-view">view</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="col-md-4 control-label">{{ trans('form.auth.confirm_password') }}<span class="required">*</span></label>
                    <div class="col-md-6">
                        <div class="outer-field">
                            {!! Form::password('confirm_password', ['class'=>'form-control padd-right35', 'id' => 'confirm_password', 'maxlength'=>14, 'autocomplete'=>'off']) !!}
                            <span class="password-view">view</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <input type="hidden" value="{{ encrypt($token) }}" name="reset_token" />
                    <div class="col-md-6 col-md-offset-4">
                        {!! Form::submit(trans('form.button.reset_password'), ['class' => 'btn btn-primary', 'title' => trans('form.button.reset_password')]) !!}
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>


</section>
@endsection
