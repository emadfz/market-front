<div class="table-responsive">
    <table class="table usertable mobile-table">
        <tbody>

            @foreach($users as $user)
            <tr>
                <td class="col1" data-title="Profile Pic">
                    <div class="thumb-pic">
                        @if(empty($user->image))

                            @if ($user->gender == 'Male')
                            <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="61" height="61">
                            @elseif ($user->gender == 'Female') 
                            <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="61" height="61">
                            @endif        
                        @else
                        <img src="{{ URL("/assets/front/img/upload/".$user->id.'/'.$user->image ) }}" alt="" width="61" height="61">
                        @endif
                    </div>
                </td>
                @if(isset($user->addressBillingDetail))
                <td class="col2" data-title="Name">{{$user->username}}<span>{{$user->addressBillingDetail->country->country_name}} 
                        of {{$user->addressBillingDetail->city->city_name}}, 
                        {{$user->addressBillingDetail->state->state_name}}</span>
                    <span class="rating">
                        <img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt="">
                    </span>
                </td>
                @else
                <td class="col2" data-title="Name">{{$user->username}}<span>Country of City, State</span><span class="rating"><img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt=""></span>
                </td>
            @endif
                <td class="col3" data-title="Profile Choice">
                    <div class="userchoice">
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-1.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-2.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-3.png" ) }}" alt="User Choice"></a>
                        <a href="#"><img src="{{ URL("/assets/front/img/userchoice-4.png" ) }}" alt="User Choice"></a>
                    </div>
                </td>
                <td class="col4" data-title="Visit Store"><a href="#">Visit Store</a></td>
                <td class="col5" data-title="Follow">
                    @if(isset($user->MingleFollowing) && $user->MingleFollowing->user_id != '')
                        <a href="javascript:void(0);" id="mingle_follow_{{$user->id}}" data-bind="{{$user->id}}" data-title="unfollowing" class="btn btn-primary btn-sm mingle_follow active" title="Unfollow">Unfollow</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_follow_{{$user->id}}" data-bind="{{$user->id}}" data-title="following" class="btn btn-primary btn-sm mingle_follow" title="Follow">Follow</a>
                    @endif
                </td>
                <td class="col5" data-title="Invitation">
                    
                    @if(isset($user->MingleInvitation) && $user->MingleInvitation->user_id != '')
                        <a href="javascript:void(0);" id="mingle_invite_{{$user->id}}" data-bind="{{$user->id}}" data-title="invited" class="btn btn-primary btn-sm active" title="Invited">Invited</a>
                    @else
                        <a href="javascript:void(0);" id="mingle_invite_{{$user->id}}" data-bind="{{$user->id}}" data-title="pending" class="btn btn-primary btn-sm mingle_invite" title="Invite">Invite</a>
                    @endif
                </td>
<!--                <td class="col6" data-title="Action">
                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Pending<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Accept</a></li>
                            <li><a href="#">Declain</a></li>
                        </ul>
                    </div>
                </td>-->
            </tr>
            @endforeach

        </tbody>
    </table>
<!--    <a href="#" class="btn btn-block showmore" title="Show More">show more</a>-->
</div>