@extends('front.profile.layout')

@section('pageContent')
<!--Rightside Start -->
          <div class="rightcol-bg clearfix profile-business">
            @include('front.profile.myprofile.partial._formbusiness',['model' => $getUserData])
          </div>
          <!--Rightside End --> 


@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['abc'=>'vvv','xyz'=>'']])
@endpush