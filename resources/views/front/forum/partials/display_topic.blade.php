<h4 class="space">{{trans('forum.common.comment')}} <span>{{date('M d, Y. H:m', strtotime($getTopicData['created_at'])) }}</span></h4>
<div class="forumsadd-outer clearfix">
    <div class="forums-profile">
        <div class="box">
            <div class="forums-pic">
                @if(empty($getTopicData['users']['image']))
                
                    @if ($getTopicData['users']['gender'] == 'Male' || !isset($item->Users['username']))
                            <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="178" height="178">
                    @elseif ($getTopicData['users']['gender'] == 'Female') 
                            <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="178" height="178">
                    @endif        
                @else
                <img src="{{ URL("/assets/front/img/upload/".$getTopicData['users']['id'].'/'.$getTopicData['users']['image'] ) }}" alt="" width="178" height="178">
                @endif
            </div>
            <p>{{isset($getTopicData['users']['username'])?$getTopicData['users']['username']:config('project.display_forum_admin_name')}}<span>Member Since : {{isset($getTopicData['users']['created_at'])?date('d M Y', strtotime($getTopicData['created_at'])):'27 Oct 2015'}}</span></p>
            <div class="rating">
                <img src="{{ URL("/assets/front/img/star-medium.png" ) }}">
                <span><strong>256</strong>Review</span>
            </div>
        </div>
    </div>
    
    <div class="forums-commentpart">
        <h3>{{$getTopicData['topic_name']}}</h3>
        <p>{{$getTopicData['topic_description']}}</p>
        <div class="add-commentcount">
            <a class="btn btn-primary" title="Add a New Comment" id="add_new_comment_forum">Add a New Comment</a>
            <span class="">{{$getTopicData['total_comments']}} Comment</span>
        </div>
        <ul class="fourms-result">
            <li class="forumtopic <?php if(isset($getTopicData['likes']['status']) &&  $getTopicData['likes']['status'] == 'like'){echo 'active';} ?>"><a href="javascript:void(0)" title="Like"><i class="likes"></i>Like<b><span id="like" data-bind="{{$getTopicData['id']}}" class="like_count{{$getTopicData['id']}}">({{$getTopicData['total_likes']}})</span></b></a></li>
            <li class="forumtopic <?php if(isset($getTopicData['likes']['status']) &&  $getTopicData['likes']['status'] == 'dislike'){echo 'active';} ?>"><a href="javascript:void(0)" title="Dislike"><i class="dislikes"></i>Dislike<b><span id="dislike" data-bind="{{$getTopicData['id']}}" class="dislike_count{{$getTopicData['id']}}">({{$getTopicData['total_dislikes']}})</span></b></a></li>
<!--            <li><a href="{{route('getReportFlag', [$getTopicData['id'],'topic'])}}" data-target="#reportabuse_ajax_modal_popup" data-toggle="modal" title="Flag"><i class="flag"></i></a></li>-->
            <?php if(!isset($getTopicData['report_flags']['id'])){ ?>
        <li id="flag{{$getTopicData['id']}}"><a href="{{route('getReportFlag', [$getTopicData['id'],'topic'])}}" data-target="#reportabuse_ajax_modal_popup" data-toggle="modal" title="Flag"><i class="flag"></i></a></li>
        <?php }else{?>
        <li class="<?php if(isset($getTopicData['report_flags']['id']) && $getTopicData['report_flags']['id'] != ''){echo 'active';} ?>"><a href="javascript::void(0)" data-toggle="modal" title="Flag"><i class="flag"></i></a></li>
        <?php } ?>
        </ul>  
    </div>
</div>
<hr>
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $('body').delegate('.forumtopic', 'click', function (e) {

            var data = $(this).find('span').attr('id');
            var forum_id = $(this).find('span').attr('data-bind');
            
             var thisdata = $(this);

            $.ajax({
                url: '{{route("updateLike")}}',
                type: 'POST',
                dataType: 'json',
                data: {data: data, forum_id: forum_id, type: 'topic'},
                success: function (r) {
                    //oTable.draw(false);
                    //toastr.success(r.msg);
                     if(data == 'like'){
                        thisdata.next().removeClass("active");
                    }else{
                        thisdata.prev().removeClass("active");
                    }
                    thisdata.addClass('active');
            
                    $('.like_count' + r.data[0].id).html('(' + r.data[0].total_likes + ')');
                    $('.dislike_count' + r.data[0].id).html('(' + r.data[0].total_dislikes + ')');
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