@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container"> 
        @stack('breadcrumb')
        @include('front.reusable.inner_menu')        
        <div class="myprofile">
            <a href="#" id="productnav" class="btn btn-info visible-xs-inline-block">Filter Option</a>
            <div class="widecol-bg clearfix bgbrown-sidebar">
                <!--Leftside Start -->
                @include('front.profile.sidebar')                
                <!--Leftside End -->
                <!--Rightside Start -->
                @yield('pageContent')
                <!--Rightside End -->
            </div>
        </div>
    </div>
</section>
@stack('modalPopup')
@endsection