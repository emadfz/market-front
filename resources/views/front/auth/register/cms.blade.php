@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li class="active">{{$cms_breadcrumb}}</li>
                </ul>
            </div>
        </div>
<!--        <h2>{{ trans('form.auth.individual_registration') }}</h2>-->
        <div class="clearfix">
            {!!$content!!}
        </div>
    </div>
</section>
@endsection