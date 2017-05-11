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
                    <li><a href="{{ route('forum') }}">{{ trans('form.common.forum') }}</a></li>
                    <li class="active">{{ trans('form.common.create') }}</li>
                </ul>
            </div>
        </div>
        <!-- END Breadcrumb -->


        <h2>{{trans('forum.common.create')}}</h2>
        <div class="forums"> 
            
            <div class="widecol-bg clearfix bg-sidebar"> 

                <!--Leftside Start -->
                @include('front.forum.partials.left_sidebar')
                <!--Leftside End --> 

                <!--Rightside Start -->
                <div class="rightcol-bg clearfix createtopic">
                    <div class="equal-column">
                    <h4>{{trans('forum.common.create')}}</h4>
                    
                    @include('front.forum._form')

                    <div class="blacktitle">Forum Rules:</div>
                    <ul class="fourms-rules">
                        <li>All Created topics to stay, unless they don't have any new comments for more than 5 months after the topic should be automatically deleted from view and search by members, but store in the system for 7 years, or downloaded to a remote storage.</li>
                        <li>All comments to stay posted unless they have less than 70% positive ratio otherwise the comment should be automatically deleted.</li>
                        <li>All members can only select "like" or "dislike", or no more than once (automated process)</li>
                        <li>Only High Rated Members or Admin can Create Topics (an automated feature based on the rating between 80% to 100 %)</li>
                        <li>The "popular Topics" tab to select all topics that have the most Comments per its posting time (automated Selection)</li>
                        <li>The "Featured Topics" to show all active topics.</li>
                        <li>Read Other. <a href="#" title="Terms and Conditions">Terms and Conditions</a></li>
                    </ul>
                    </div>
                </div>
                <!--Rightside End --> 
            </div>
        </div>



    </div>  			
</section>
@endsection