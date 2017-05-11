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
                    <li class="active">{{ trans('form.common.received_invitation') }}</li>                    
                </ul>
            </div>
        </div>
        <!-- END Breadcrumb -->

        <h2>{{trans('mingle.common.title')}}</h2>
        <div class="getconnect-page"> 
            <div class="widecol-bg clearfix bg-sidebar"> 
                <!--Leftside Start -->
                <div class="leftcol-bg"> 
                    
                    <!--Leftside End --> 
                    <!--Leftside Start -->
                    @include('front.mingle.partials.left_link_sidebar')
                </div>
                <!--Leftside End --> 
                <!--Rightside Start -->
                <?php $condition = ( isset($sentUser) && empty($sentUser) ); ?>
                <div class="rightcol-bg clearfix {{ ($condition)?'mingle-messagelist':'mingle-messagepage' }} ">
                    @if($condition)                    
                        @include('front.mingle.partials.messageUserList')
                    @else
                        @include('front.mingle.partials.messageUser')
                    @endif           
                </div>
                <!--Rightside End --> 
            </div>
        </div>



    </div>  			
</section>
@endsection

