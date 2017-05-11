@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li class="active">{{ trans('form.auth.business_registration') }}</li>
                </ul>
            </div>
        </div>
        <h2>Business Registration</h2>
        <div class="widecolumn registration">
            <ul class="registration-step">
                <li><a href="javascript:;"><span>1</span></a></li>
                <li><a href="javascript:;"><span>2</span></a></li>
                <li><a href="javascript:;"><span>3</span></a></li>
                <li><a href="javascript:;"><span>4</span></a></li>
            </ul>
            {!! Form::open(['route' => 'businessRegister', 'class' => 'biz-ajax', 'id' => 'business-register-form']) !!}
            @include('front.auth.register.business_registration-step1')
            @include('front.auth.register.business_registration-step2')
            @include('front.auth.register.business_registration-step3')
            @include('front.auth.register.business_registration-step4')
            {!! Form::close() !!}

        </div>
    </div>			
</section>
@endsection

@push('scripts')
<script src="{{ asset('assets/front/js/business-registration.js') }}"></script>
@endpush('scripts')