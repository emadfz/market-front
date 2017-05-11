@if($type != 'sent' && $type != 'pending')
<div class="table-responsive">
    <table class="table usertable mobile-table">
        <tbody>            
            @if (count($users) >= 1)
            @foreach($users as $data)
            
            <tr>
<!--                <td class="col1" data-title="Profile Pic"><div class="thumb-pic"><img src="bootstrap/img/fourms-avtar.jpg" alt="Avtar" width="61" height="61"></div></td>-->
                <td class="col1" data-title="Profile Pic">
                    <div class="thumb-pic">
                        @if(empty($data->sentUser->image))

                        @if ($data->sentUser->gender == 'Male')
                            <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="61" height="61">
                        @elseif ($data->sentUser->gender == 'Female') 
                            <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="61" height="61">
                        @endif        
                        @else
                            <img src="{{ URL("/assets/front/img/upload/".$data->sentUser->id.'/'.$data->sentUser->image ) }}" alt="" width="61" height="61">
                        @endif
                    </div>
                </td>
                <td class="col2" data-title="Name">{{$data->sentUser->username}}
                    <span class="rating"><img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt=""></span>
                    @if(isset($data->mingle_followers_id) && $data->mingle_followers_id != '')
                        <span>Follower</span>
                    @endif
                </td>
                <td class="col3" data-title="Profile Choice">
                    <div class="userchoice">
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-1.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-2.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-3.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-4.png" ) }}" alt="User Choice"></a>
                    </div>
                </td>
                <td class="col4" data-title="Visit Store"><a href="#">Visit Store</a></td>
<!--                <td class="col5" data-title="Follow"><a href="#" class="btn btn-primary btn-sm" title="Follow">Follow</a></td>-->
                <td class="col5" data-title="Follow">
                    @if(isset($data->mingle_following_id) && $data->mingle_following_id != '')
                        <a href="javascript:void(0);" id="mingle_follow_{{$data->sentUser->id}}" data-bind="{{$data->sentUser->id}}" data-title="unfollowing" class="btn btn-primary btn-sm mingle_follow active" title="Unfollow">Unfollow</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_follow_{{$data->sentUser->id}}" data-bind="{{$data->sentUser->id}}" data-title="following" class="btn btn-primary btn-sm mingle_follow" title="Follow">Follow</a>
                    @endif
                </td>


                @if($type == 'pending')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pending<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="accept" class="mingle_dropdown">Accept</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="decline" class="mingle_dropdown">Decline</a></li>
                        </ul>
                    </div>
                </td>
                @elseif($type == 'sent')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$data->status}}
<!--                            <span class="caret"></span>-->
                        </a>
<!--                        <ul class="dropdown-menu">-->
<!--                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="archive" class="mingle_dropdown">Cancel Invitation</a></li>-->
<!--                        </ul>-->
                    </div>
                </td>
                @elseif($type == 'accept')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="msg-icon"></i>
                                New
                            <em>
                                ({{ (isset($data->inviation_id) && (isset($messages) && !empty($messages)) )?$messages->where('fromUserId',@$data->inviation_id)->where('is_read','No')->count():'0' }})
                            </em>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{URl('mingle/messages/'.encrypt($data->sentUser->id))}}">Send Message</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="archive" class="mingle_dropdown">Archive</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="block" class="mingle_dropdown">Block</a></li>
                        </ul>
                    </div>
                </td>
                @elseif($type == 'block')
                <td class="col6" data-title="Action">
                    <div class="dropdown denied">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Block<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="accept" class="mingle_dropdown">Unblock</a></li>
                        </ul>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
            @else
            <tr><td class="nodata">No record found</td></tr>
            @endif
        </tbody>
    </table>
    

</div>
@elseif($type == 'pending')
<div class="table-responsive">
    <table class="table usertable mobile-table">
        <tbody>
            @if (count($users) >= 1)
            @foreach($users as $data)
            <tr>
<!--                <td class="col1" data-title="Profile Pic"><div class="thumb-pic"><img src="bootstrap/img/fourms-avtar.jpg" alt="Avtar" width="61" height="61"></div></td>-->
                <td class="col1" data-title="Profile Pic">
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
                </td>
                <td class="col2" data-title="Name">{{$data->user->username}}
                    <span class="rating"><img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt=""></span>
                    @if(isset($data->mingle_followers_id) && $data->mingle_followers_id != '')
                        <span>Follower</span>
                    @endif
                </td>
                <td class="col3" data-title="Profile Choice">
                    <div class="userchoice">
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-1.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-2.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-3.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-4.png" ) }}" alt="User Choice"></a>
                    </div>
                </td>
                <td class="col4" data-title="Visit Store"><a href="#">Visit Store</a></td>
<!--                <td class="col5" data-title="Follow"><a href="#" class="btn btn-primary btn-sm" title="Follow">Follow</a></td>-->
                <td class="col5" data-title="Follow">
                    @if(isset($data->mingle_following_id) && $data->mingle_following_id != '')
                        <a href="javascript:void(0);" id="mingle_follow_{{$data->user->id}}" data-bind="{{$data->user->id}}" data-title="unfollowing" class="btn btn-primary btn-sm mingle_follow active" title="Unfollow">Unfollow</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_follow_{{$data->user->id}}" data-bind="{{$data->user->id}}" data-title="following" class="btn btn-primary btn-sm mingle_follow" title="Follow">Follow</a>
                    @endif
                </td>


                @if($type == 'pending')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pending<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="accept" class="mingle_dropdown">Accept</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="decline" class="mingle_dropdown">Decline</a></li>
                        </ul>
                    </div>
                </td>
                @elseif($type == 'sent')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$data->status}}
<!--                            <span class="caret"></span>-->
                        </a>
<!--                        <ul class="dropdown-menu">-->
<!--                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="archive" class="mingle_dropdown">Cancel Invitation</a></li>-->
<!--                        </ul>-->
                    </div>
                </td>
                @elseif($type == 'accept')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="msg-icon"></i>New<em>(20)</em><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Send Message</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="archive" class="mingle_dropdown">Archive</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="block" class="mingle_dropdown">Block</a></li>
                        </ul>
                    </div>
                </td>
                @elseif($type == 'block')
                <td class="col6" data-title="Action">
                    <div class="dropdown denied">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Block<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="accept" class="mingle_dropdown">Unblock</a></li>
                        </ul>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
            @else
            <tr><td class="nodata">No record found</td></tr>
            @endif
        </tbody>
    </table>
    

</div>

@else
<div class="table-responsive">
    <table class="table usertable mobile-table">
        <tbody>
            @if (count($users) >= 1)
            @foreach($users as $data)
            <tr>
<!--                <td class="col1" data-title="Profile Pic"><div class="thumb-pic"><img src="bootstrap/img/fourms-avtar.jpg" alt="Avtar" width="61" height="61"></div></td>-->
                <td class="col1" data-title="Profile Pic">
                    <div class="thumb-pic">
                        @if(empty($data->sentUser->image))

                        @if ($data->sentUser->gender == 'Male')
                        <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="61" height="61">
                        @elseif ($data->sentUser->gender == 'Female') 
                        <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="61" height="61">
                        @endif        
                        @else
                        <img src="{{ URL("/assets/front/img/upload/".$data->sentUser->id.'/'.$data->sentUser->image ) }}" alt="" width="61" height="61">
                        @endif
                    </div>
                </td>
                <td class="col2" data-title="Name">{{$data->sentUser->username}}
                    <span class="rating"><img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt=""></span>
                    @if(isset($data->mingle_followers_id) && $data->mingle_followers_id != '')
                        <span>Follower</span>
                    @endif
                </td>
                <td class="col3" data-title="Profile Choice">
                    <div class="userchoice">
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-1.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-2.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-3.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-4.png" ) }}" alt="User Choice"></a>
                    </div>
                </td>
                <td class="col4" data-title="Visit Store"><a href="#">Visit Store</a></td>
<!--                <td class="col5" data-title="Follow"><a href="#" class="btn btn-primary btn-sm" title="Follow">Follow</a></td>-->
                <td class="col5" data-title="Follow">
                    @if(isset($data->mingle_following_id) && $data->mingle_following_id != '')
                        <a href="javascript:void(0);" id="mingle_follow_{{$data->sentUser->id}}" data-bind="{{$data->sentUser->id}}" data-title="unfollowing" class="btn btn-primary btn-sm mingle_follow active" title="Unfollow">Unfollow</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_follow_{{$data->sentUser->id}}" data-bind="{{$data->sentUser->id}}" data-title="following" class="btn btn-primary btn-sm mingle_follow" title="Follow">Follow</a>
                    @endif
                </td>


                @if($type == 'pending')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pending<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="accept" class="mingle_dropdown">Accept</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="decline" class="mingle_dropdown">Decline</a></li>
                        </ul>
                    </div>
                </td>
                @elseif($type == 'sent')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{$data->status}}
<!--                            <span class="caret"></span>-->
                        </a>
<!--                        <ul class="dropdown-menu">-->
<!--                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="archive" class="mingle_dropdown">Cancel Invitation</a></li>-->
<!--                        </ul>-->
                    </div>
                </td>
                @elseif($type == 'accept')
                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <i class="msg-icon"></i>New<em>(20)</em><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Send Message</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="archive" class="mingle_dropdown">Archive</a></li>
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="block" class="mingle_dropdown">Block</a></li>
                        </ul>
                    </div>
                </td>
                @elseif($type == 'block')
                <td class="col6" data-title="Action">
                    <div class="dropdown denied">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Block<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="javascript:void(0);" data-bind="{{$data->id}}" data-title="accept" class="mingle_dropdown">Unblock</a></li>
                        </ul>
                    </div>
                </td>
                @endif
            </tr>
            @endforeach
            @else
            <tr><td class="nodata">No record found</td></tr>
            @endif
        </tbody>
    </table>
    

</div>
@endif