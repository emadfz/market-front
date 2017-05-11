@extends('front.layouts.app')

@section('content')
<section class="content">
    <div class="container">
        <!-- BEGIN Breadcrumb -->
<!--        <div class="row">
            <div class="col-md-12">
                <ul class="breadcrumb">
                    <li><a href="{{ route('homepage') }}">{{ trans('form.common.home') }}</a></li>
                    <li><a href="{{ route('getConnect') }}">{{ trans('form.common.mingle') }}</a></li>
                    <li class="active">Sync</li>
                </ul>
            </div>
        </div>-->
        <!-- END Breadcrumb -->

    <h2>{{trans('mingle.common.sync')}}</h2>
    
        <div class="sync-mingle-page"> 
             <div class="widecol-bg clearfix bg-sidebar"> 
                 <!--Leftside Start -->
                <div class="leftcol-bg"> 

                </div>
                <!--Leftside End --> 
                <!--Rightside Start -->
                <div class="rightcol-bg clearfix nopadding">
                
                @include('front.mingle.partials.right_sync_bar')
                </div>
                
             </div>
        </div>
    
    </div>  			
</section>
@endsection