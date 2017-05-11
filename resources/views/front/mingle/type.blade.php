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
                    @if($type == 'pending')
                    <li class="active">{{ trans('form.common.received_invitation') }}</li>
                    @elseif($type == 'sent')
                    <li class="active">{{ trans('form.common.sent_invitation') }}</li>
                    @elseif($type == 'accept')
                    <li class="active">{{ trans('form.common.my_connections') }}</li>
                    @elseif($type == 'block')
                    <li class="active">{{ trans('form.common.block_invitation') }}</li>
                    @elseif($type == 'archive')
                    <li class="active">{{ trans('form.common.archive_invitation') }}</li>
                    @endif
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
                <div class="rightcol-bg clearfix mingle-typepage">
                    <h4 class="nomargin">
                        @if($type == 'pending')
                        {{ trans('form.common.received_invitation') }}
                        @elseif($type == 'sent')
                        {{ trans('form.common.sent_invitation') }}
                        @elseif($type == 'accept')
                        {{ trans('form.common.my_connections') }}
                        @elseif($type == 'block')
                        {{ trans('form.common.block_invitation') }}
                        @elseif($type == 'archive')
                        {{ trans('form.common.archive_invitation') }}
                        @endif

                    </h4>
                    <div class="mingle-typepage-partials">
                    @include('front.mingle.partials.type_right')
                    </div>
                    <a href="#" class="btn btn-block showmore mingle_showmore" title="Show More">show more</a>
                </div>
                <!--Rightside End --> 
            </div>
        </div>



    </div>  			
</section>
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
  
  
   $( "body" ).delegate( ".mingle_dropdown", "click", function(event, state) {
        
        var id = $(this).attr('data-bind');
        var status = $(this).attr('data-title');
        var type = '{{$type}}';
        var thisdata = $(this);
         $.ajax({
        url: '{{route("mingleStatus")}}',
                type: 'POST',
                dataType: 'json',
                data: {id: id,status:status,type:type,nextPage:nextPage},
                success: function (r) {
                    if (r.status == 'success' && r.html != ''){
                         $('.mingle-typepage-partials').html('');
                        $('.mingle-typepage-partials').append(r.html);
                        toastr.success("{{ trans('mingle.validation_message.status') }}");
                        nextPage = r.nextPage;
                        }else {
                        alert('Could not get the Data. Please contact Administrator!!');
                        return false;
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