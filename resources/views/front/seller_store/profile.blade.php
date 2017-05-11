@extends('front.layouts.app')
@section('content')
<section class="content">
  <div class="container"> 
 
    <!--breadcrumb Start-->
    <div class="row">
      <div class="col-md-12">
        <ul class="breadcrumb">
          <li><a href="index.html">Home</a></li>
          <li class="active">Seller's Profile</li>
          <li class="active">{{$user->first_name}} {{$user->last_name}} </li>
        </ul>
      </div>
    </div>
    {{-- {{ $user }} --}}
    <!--breadcrumb Start-->
    <h2>Seller's Profile</h2>
    <div class="sellerProfile clearfix"> 
      <!--Rightside Start -->
      <div class="rightcol-bg clearfix sellerProfile-bg">
        <div class="head clearfix"> <span> <a href="#" title="Contact Seller">Contact Seller</a> <a href="#" title="Visit Store">Visit Store</a> </span> </div>
        <div class="forumsadd-outer clearfix">
          <div class="forums-profile">
            <div class="box">
              <div class="forums-pic"> <img src="{{getUserProfileImage($user)}}" alt="" width="178" height="178"> </div>
              <p>{{$user->title}} {{$user->first_name}} {{$user->last_name}}<span>Member Since : {{$user->created_at->month}} ,{{$user->created_at->year}}</span></p>
                <div id="rateYo"></div>

              <div class="rating"> <img src="bootstrap/img/star-medium.png" alt="User Choice"> <span><strong>{{count($feedbacks)}}</strong>
              Review @if(count($feedbacks) >1)(s) @endif </span> </div>
            </div>
          </div>
          <div class="forums-commentpart">
            <div class="row">
              <div class="col-md-4 memberpart">
                <h4>Description of business:</h4>

                @if(!empty($user_details))
                <p>{{$user_details->business_details}}</p>
                @endif
              </div>
              <div class="col-md-8 sellmember">
                <div class="clearfix"> <a href="{{route('forumMostRecent')}}" class="btn btn-lightgray" title="View Forum Topics">View Forum Topics</a> 
                 @if(\Auth::check())
                  @if(isset($user->MingleInvitation) && $user->MingleInvitation->user_id != '')
                        <a href="javascript:void(0);" id="mingle_invite_{{$user->id}}" data-bind="{{$user->id}}" data-title="invited" class="btn btn-primary btn-sm active" title="Invited">Invited</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_invite_{{$user->id}}" data-bind="{{$user->id}}" data-title="pending" class="btn btn-primary btn-sm mingle_invite" title="Invite">Invite</a>
                    @endif
                {{-- <a href="#" class="btn btn-lightgray" title="Send Mingle Invitation">Send Mingle Invitation</a>  --}}
                <a href="#" class="btn btn-border" title="Block this Member">Block this Member</a> </div>
                @endif

                <div id="productviewrange">
                  <h4>Members Following Me<span>({{count($user_followers)}})</span></h4>
                  <ul class="sellerPicSlider" >
              
                  @if (!empty($user_followers))
                    @foreach($user_followers as $follower)
                    <li><img src="{{getUserProfileImage($follower->user)}}" width="50" height="48" alt="Members"/></li>
                    @endforeach
                  @endif

                  </ul>
                </div>
                 @if(\Auth::check())

                @if(isset($user->MingleFollowing) && $user->MingleFollowing->user_id != '')
                        <a href="javascript:void(0);" id="mingle_follow_{{$user->id}}" data-bind="{{$user->id}}" data-title="unfollowing" class="btn btn-primary btn-sm mingle_follow active" title="Unfollow">Unfollow</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_follow_{{$user->id}}" data-bind="{{$user->id}}" data-title="following" class="btn btn-primary btn-sm mingle_follow" title="Follow">Follow</a>
                    @endif
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--Feedback Type Tab start-->
      <div class="customtab seller-feedback"> 
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a href="#feedbackall" role="tab" data-toggle="tab">All Feedback</a></li>
          <li role="presentation"><a href="#feedback_seller" role="tab" data-toggle="tab">Feedback as a seller</a></li>
          <li role="presentation"><a href="#feedback_buyer" role="tab" data-toggle="tab">Feedback as a buyer</a></li>
          <li role="presentation"><a href="#feedback_forme" role="tab" data-toggle="tab">Feedback left for me</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content clearfix">
          <div role="tabpanel" class="tab-pane active" id="feedbackall">
            <div class="row">
              <div class="col-md-8">
                <table class="table mobile-table feedback-table">
                  <tbody>
                  @if(!empty($feedbacks))
                    @foreach($feedbacks as $feedback)
                      <tr>
                        <td class="col1" data-title="Description"><div class="title">Lorem Ipsum is simply dummy<span>{{$feedback->description}}</span></div></td>
                        <td class="col2" data-title="Rating">Joh***e<span><img height="20px" width="50px" src="http://3.bp.blogspot.com/-gzYPlTPTj6Y/UTEjYGzsRjI/AAAAAAAAFfo/m2hbgTPEcTY/s1600/Four+stars.png" alt="Rating"></span></td>
                        <td class="col3" data-title="Date">{{$feedback->created_at->day . '-' . $feedback->created_at->month . '-' . $feedback->created_at->year}}</td>
                      </tr>
                    @endforeach
                  @endif
                    {{-- <tr>
                      <td class="col1" data-title="Description"><div class="title">Lorem Ipsum is simply dummy<span>Lorem Ipsum is simply dummy text of the printing and typesetting</span></div></td>
                      <td class="col2" data-title="Rating">Joh***e<span><img src="bootstrap/img/star-medium.png" alt="Rating"></span></td>
                      <td class="col3" data-title="Date">19-12-2015</td>
                    </tr>
                    <tr>
                      <td class="col1" data-title="Description"><div class="title">Lorem Ipsum is simply dummy<span>Lorem Ipsum is simply dummy text of the printing and typesetting</span></div></td>
                      <td class="col2" data-title="Rating">Joh***e<span><img src="bootstrap/img/star-medium.png" alt="Rating"></span></td>
                      <td class="col3" data-title="Date">19-12-2015</td>
                    </tr>
                    <tr>
                      <td class="col1" data-title="Description"><div class="title">Lorem Ipsum is simply dummy<span>Lorem Ipsum is simply dummy text of the printing and typesetting</span></div></td>
                      <td class="col2" data-title="Rating">Joh***e<span><img src="bootstrap/img/star-medium.png" alt="Rating"></span></td>
                      <td class="col3" data-title="Date">19-12-2015</td>
                    </tr> --}}
                  </tbody>
                </table>
                <a href="#" class="btn btn-block showmore" title="See all">See all</a> </div>
                <div class="col-md-4 sellerRating">
                  <div class="title-ltr">Seller ratings<span>(last 12 months)</span></div>	
                  <table class="table mobile-table sellerrating-table">
                    <thead>
                      <tr>
                        <th class="col1">Criteria</th>
                        <th class="col2">Average rating</th>
                        <th class="col3">Number of ratings</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="col1" data-title="Criteria">Item as described </td>
                        <td class="col2" data-title="Rating"><img src="bootstrap/img/star-medium.png" alt="Rating"></td>
                        <td class="col3" data-title="No. Rating">3333</td>
                      </tr>
                      <tr>
                        <td class="col1" data-title="Criteria">Communication</td>
                        <td class="col2" data-title="Rating"><img src="bootstrap/img/star-medium.png" alt="Rating"></td>
                        <td class="col3" data-title="No. Rating">3335</td>
                      </tr>
                      <tr>
                        <td class="col1" data-title="Criteria">Shipping time</td>
                        <td class="col2" data-title="Rating"><img src="bootstrap/img/star-medium.png" alt="Rating"></td>
                        <td class="col3" data-title="No. Rating">3333 </td>
                      </tr>
                      <tr>
                        <td class="col1" data-title="Criteria">Shipping and handling charges</td>
                        <td class="col2" data-title="Rating"><img src="bootstrap/img/star-medium.png" alt="Rating"></td>
                        <td class="col3" data-title="No. Rating">4215</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="title-ltr">Feedback ratings<span>(last 12 months)</span></div>
                  <div class="text-center mrg-top10"><img src="bootstrap/img/progress.jpg" alt="Progress"></div>
                </div>
              </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="feedback_seller">2</div>
            <div role="tabpanel" class="tab-pane" id="feedback_buyer">3</div>
            <div role="tabpanel" class="tab-pane" id="feedback_forme">4</div>
          </div>
        </div>
        <!--Feedback Type Tab Close--> 

        <!--Rightside End --> 
      </div>
    </div>
  </section>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.css">
  <!-- Latest compiled and minified JavaScript -->

  @endsection

 
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {


<?php if ($usersCount > config('project.mingle_listing_limit')) { ?>
        var nextPage = 2;
<?php } else { ?>
        $('.mingle_showmore').remove();
        var nextPage = 1;
<?php } ?>
    var totalPage = {{ceil($usersCount / config('project.mingle_listing_limit'))}}

    $('body').delegate('.mingle_showmore', 'click', function(event, state) {
    if (totalPage == nextPage){
    $('.mingle_showmore').remove();
    }
    var selfObj = $(this);
    $.ajax({
    type: "get",
            url: window.location.href + '/' + nextPage,
            async: true,
            dataType:'json',
            data:'',
            success: function (response) {
            if (response.status == 'success' && response.html != ''){
            $('.mingle-typepage-partials').append(response.html);
            nextPage = response.nextPage;
            }
            else if (response.status == 'success' && response.html == ''){
            selfObj.remove();
            }
            else {
            alert('Could not get the Data. Please contact Administrator!!');
            return false;
            }
            }
    });
    });
    
    $( "body" ).delegate( ".mingle_follow", "click", function(event, state) {
        
        var following_id = $(this).attr('data-bind');
        var following_type = $(this).attr('data-title');
        var thisdata = $(this);
         $.ajax({
        url: '{{route("mingleFollow")}}',
                type: 'POST',
                dataType: 'json',
                data: {following_id: following_id,following_type:following_type},
                success: function (r) {
                    if(following_type == 'following'){
                        thisdata.addClass('active');
                        thisdata.text('Unfollow');
                        thisdata.attr('data-title','unfollowing');
                        thisdata.attr('title','Unfollow');
                        //toastr.success("{{ trans('mingle.validation_message.following') }}");
                    }else{
                        thisdata.removeClass('active');
                        thisdata.text('follow');
                        thisdata.attr('data-title','following');
                        thisdata.attr('title','follow');
                        //toastr.success("{{ trans('mingle.validation_message.unfollowing') }}");
                    }
                },
                error: function (data) {
                    if (data.status === 401) {
                    //window.location="{{URL::to('individual-register')}}";
                    $(".signinModal").click();
                    }
                if (data.status === 422) {
                toastr.error("{{ trans('message.failure') }}");
                }
                }
        });
  });
  
 
  
    });
</script>
@endpush