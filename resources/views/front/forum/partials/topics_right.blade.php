

@if (count($topics) >= 1)
@foreach ($topics as $item)
<div class="media"> 
    <div class="media-left">
         @if(empty($item->Users['image']))
                
                    @if ($item->Users['gender'] == 'Male' || !isset($item->Users['username']))
                            <img class="media-object" src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="61" height="61">
                    @elseif ($item->Users['gender'] == 'Female') 
                            <img class="media-object" src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="61" height="61">
                    @endif        
                @else
                <img class="media-object" src="{{ URL("/assets/front/img/upload/".$item->Users['id'].'/'.$item->Users['image'] ) }}" alt="" width="61" height="61">
                @endif
<!--                <img class="media-object" src="{{ URL("/assets/front/img/fourms-avtar.jpg" ) }}" alt="Avtar" width="61" height="61">-->
    </div> 
    <div class="media-body">
        <h4 class="media-heading">{{isset($item->Users['username'])?$item->Users['username']:config('project.display_forum_admin_name')}},
            <span class="date">{{date('M d, Y. H:m', strtotime($item->created_at)) }}</span>
<!--                <span class="date">Dec 11, 2015. 11:45,</span>
            <span class="replies">77,545 Replies</span>-->
        </h4>
        <p><b><a href="{{ route('getTopic',[$item->topic_department_id,$item->id]) }}">{{$item->topic_name}}</a></b></p>
        <p class="more">{{$item->topic_description}}</p> 
    </div>
    <ul class="fourms-result">
        <li><a href="{{ route('getTopic',[$item->topic_department_id,$item->id]) }}"><span>{{$item->total_comments}}</span>Comment</a></li>
        <li class="forumtopic <?php if(isset($item->Likes['status']) &&  $item->Likes['status'] == 'like'){echo 'active';} ?>"><a href="javascript:void(0)" title="Like"><i class="likes"></i>Like<b><span id="like" data-bind="{{$item->id}}" class="like_count{{$item->id}}">({{$item->total_likes}})</span></b></a></li>
        <li class="forumtopic <?php if(isset($item->Likes['status']) &&  $item->Likes['status'] == 'dislike'){echo 'active';} ?>"><a href="javascript:void(0)" title="Dislike"><i class="dislikes"></i>Dislike<b><span id="dislike" data-bind="{{$item->id}}" class="dislike_count{{$item->id}}">({{$item->total_dislikes}})</span></b></a></li>
        <?php if(!isset($item->ReportFlags['id'])){ ?>
        <li id="flag{{$item->id}}"><a href="{{route('getReportFlag', [$item->id,'topic'])}}" data-target="#reportabuse_ajax_modal_popup" data-toggle="modal" title="Flag"><i class="flag"></i></a></li>
        <?php }else{?>
        <li class="<?php if(isset($item->ReportFlags['id']) && $item->ReportFlags['id'] != ''){echo 'active';} ?>"><a href="javascript::void(0)" data-toggle="modal" title="Flag"><i class="flag"></i></a></li>
        <?php } ?>
            
    </ul> 
</div>
@endforeach

@else
<p class="nodata"> No Record Found </p>
@endif



