@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <!-- BEGIN Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li><a href="{{ route('getConnect') }}">{{ trans('form.common.mingle') }}</a></li>
                    <li class="active">{{ trans('form.common.getconnect') }}</li>
                </ul>
            </div>
        </div>
        <!-- END Breadcrumb -->

    <h2>{{trans('mingle.common.title')}}</h2>
        <div class="getconnect-page"> 
            <a href="#" id="productnav" class="btn btn-info visible-xs-inline-block">Filter Option</a>
            <div class="widecol-bg clearfix bg-sidebar"> 
                <!--Leftside Start -->
                <div class="leftcol-bg"> 
                @include('front.mingle.partials.left_search_sidebar')
                <!--Leftside End --> 
                <!--Leftside Start -->
                @include('front.mingle.partials.left_link_sidebar')
                </div>
                <!--Leftside End --> 
                <!--Rightside Start -->
                <div class="rightcol-bg clearfix nopadding">
                @include('front.mingle.partials.right_slider_sidebar')
                <!--Rightside End --> 
                <!--Rightside Start -->
                @include('front.mingle.partials.right_contain_sidebar')
                </div>
                <!--Rightside End --> 
            </div>
        </div>



    </div>  			
</section>
@endsection