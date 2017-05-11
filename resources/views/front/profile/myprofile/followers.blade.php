@extends('front.profile.layout')

@section('pageContent')
<!--Rightside Start -->
<div class="rightcol-bg clearfix profile-follow">
    @include('front.profile.myprofile.partial._formfollowers')
</div>
<!--Rightside End --> 


@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['abc'=>'vvv','xyz'=>'']])
@endpush

@push('scripts')
<script>

    $("body").delegate(".mingle_follow", "click", function (event, state) {

        var following_id = $(this).attr('data-bind');
        var following_type = $(this).attr('data-title');
        var thisdata = $(this);
        $.ajax({
            url: '{{route("mingleUnFollow")}}',
            type: 'POST',
            dataType: 'json',
            data: {following_id: following_id, following_type: following_type},
            success: function (response) {
                if (response.status == 'success' && response.html != '') {
                    
                    var tabId = $('.tab-pane.active').attr('id');
                    var tabParentId = $('.tab-pane-li.active').attr('id');
                    
                    $('.profile-follow').html(response.html);
                    
                    $('.tab-pane').removeClass('active');
                    $('#'+tabId).addClass('active');
                    $('.tab-pane-li').removeClass('active');
                    $('#'+tabParentId).addClass('active');

                } else if (response.status == 'success' && response.html == '') {
                    selfObj.remove();
                } else {
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

</script>
@endpush