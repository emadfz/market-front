@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <!-- BEGIN Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li><a href="#">{{ trans('form.common.mingle') }}</a></li>
                    <li class="active">{{ trans('form.common.forum') }}</li>
                </ul>
            </div>
        </div>
        <!-- END Breadcrumb -->

        <h2>{{trans('forum.common.title')}}</h2>
        <div class="forums"> 

            <div class="widecol-bg clearfix bg-sidebar"> 
                <!--Leftside Start -->
                @include('front.forum.partials.left_sidebar')
                <!--Leftside End --> 
                <!--Rightside Start -->
                @include('front.forum.partials.right_sidebar')
                <!--Rightside End --> 
            </div>
        </div>



    </div>  			
</section>
@endsection