@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        @stack('breadcrumb')
        @include('front.reusable.inner_menu')
        <div class="myactivity">
            <a href="#" id="productnav" class="btn btn-info visible-xs-inline-block">Filter Option</a>
            <div class="widecol-bg clearfix">
                <!--Leftside Start -->
                @include('front.buy.sidebar')
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