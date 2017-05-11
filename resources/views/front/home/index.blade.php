@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <!-- BEGIN Breadcrumb -->
        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li class="active">Home</li>
                </ul>
            </div>
        </div>
        <!-- END Breadcrumb -->
        @include('front.home.partials.main_slider')
    </div>  			
</section>

<!-- BEGIN Product Slider -->
@include('front.home.partials.product_slider')
<!-- END Product Slider -->

@endsection