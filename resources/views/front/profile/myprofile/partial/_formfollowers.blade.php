<div class="equal-column">
    <h4 class="bghead">Follow / Following</h4>
    <div class="customtab follower-tab">
        <!--Profile Follow/Follwing Start-->
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li id="tab1" role="presentation" class="tab-pane-li active"><a href="#ifollow" role="tab" data-toggle="tab">Members I Follow</a></li>
            <li id="tab2" role="presentation" class="tab-pane-li"><a href="#followingme" role="tab" data-toggle="tab">Members Following Me</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="ifollow">
                <h5 class="blacktitle">Members I Follow ({{$followingCount}})</h5>
                <hr class="mrg-topnone">
                <ul class="followlist clearfix">
                    @if($followingCount)
                        @foreach($following as $data)
                        <li>
                            <div class="thumb-pic">
                                @if(empty($data->followUser->image))

                                @if ($data->followUser->gender == 'Male')
                                <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="61" height="61">
                                @elseif ($data->followUser->gender == 'Female') 
                                <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="61" height="61">
                                @endif        
                                @else
                                <img src="{{ URL("/assets/front/img/upload/".$data->followUser->id.'/'.$data->followUser->image ) }}" alt="" width="61" height="61">
                                @endif
                            </div>
                            <div class="title">{{$data->followUser->username}}</div>
                            <a class="close tooltipevent mingle_follow" href="javascript:void(0);" id="mingle_follow_{{$data->id}}" data-bind="{{$data->id}}" data-title="unfollowing" title="Delete">Delete</a>
<!--                            <span class="custom-tooltip">
                                <a class="mingle_follow" href="javascript:void(0);" id="mingle_follow_{{$data->followUser->id}}" data-bind="{{$data->followUser->id}}" data-title="unfollowing" title="Remove">Remove</a>
                                    <a href="#" title="Block">Block</a>
                            </span>-->
                        </li>
                        @endforeach
                    @else
                        <p class="nodata">No Record Found</p>
                    @endif

                </ul>
            </div>
            <div role="tabpanel" class="tab-pane" id="followingme">
                <h5 class="blacktitle">Members Following Me ({{$followersCount}})</h5>
                <hr class="mrg-topnone">
                <ul class="followlist clearfix">
                    @if($followingCount)
                        @foreach($followers as $data)
                        <li>
                            <div class="thumb-pic">
                                @if(empty($data->user->image))

                                @if ($data->user->gender == 'Male')
                                <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="61" height="61">
                                @elseif ($data->user->gender == 'Female') 
                                <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="61" height="61">
                                @endif        
                                @else
                                <img src="{{ URL("/assets/front/img/upload/".$data->user->id.'/'.$data->user->image ) }}" alt="" width="61" height="61">
                                @endif
                            </div>
                            <div class="title">{{$data->user->username}}</div>
                            <a class="close tooltipevent" href="javascript:void(0);" id="mingle_follow_{{$data->id}}" data-bind="{{$data->id}}" data-title="unfollowing" title="Delete">Delete</a>
<!--                            <span class="custom-tooltip">
                                <a href="#" title="Remove">Remove</a>
                                <a href="#" title="Block">Block</a>
                            </span>-->
                        </li>

                        @endforeach
                    @else
                        <p class="nodata">No Record Found</p>
                    @endif
                </ul>
            </div>
        </div>
    </div>


    <!--Profile Follow/Follwing  End--> 
</div>