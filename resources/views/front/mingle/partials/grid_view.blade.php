<div role="tabpanel" class="tab-pane active clearfix" id="gridview">

    <ul class="user-block clearfix">
        @foreach($users as $user)
        <li class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
            <div class="img-outer">
                
                @if(empty($user->image))
                
                    @if ($user->gender == 'Male')
                            <img src="{{ URL("/assets/front/img/upload/user-male.png" ) }}" alt="" width="111" height="99">
                    @elseif ($user->gender == 'Female') 
                            <img src="{{ URL("/assets/front/img/upload/user-female.png" ) }}" alt="" width="111" height="99">
                    @endif        
                @else
                <img src="{{ URL("/assets/front/img/upload/".$user->id.'/'.$user->image ) }}" alt="" width="168" height="168">
                @endif
                <span class="bgoverly"></span>
            </div>
            <div class="userchoice">
                <a href="#"><img src="{{ URL("/assets/front/img/userchoice-1.png" ) }}" width="17" height="24" alt="User Choice"></a>
                <a href="#"><img src="{{ URL("/assets/front/img/userchoice-2.png" ) }}" width="24" height="17" alt="User Choice"></a>
                <a href="#"><img src="{{ URL("/assets/front/img/userchoice-3.png" ) }}" width="28" height="15" alt="User Choice"></a>
                <a href="#"><img src="{{ URL("/assets/front/img/userchoice-4.png" ) }}" width="15" height="16" alt="User Choice"></a>
            </div>
            @if(isset($user->addressBillingDetail))
<!--            <h5>Country of City, State</h5>-->
                <h5> {{$user->username}} <span>
                        {{$user->addressBillingDetail->country->country_name}} 
                        of {{$user->addressBillingDetail->city->city_name}}, 
                        {{$user->addressBillingDetail->state->state_name}}
                     </span>
                </h5>
            @else
            <h5>{{$user->username}}<span>Country of City, State</span></h5>
            @endif
            <div class="rating">
                <img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt="">
            </div>
        </li>
        @endforeach
    </ul>
<!--    <a href="#" class="btn btn-block showmore" title="Show More">show more</a>-->
</div>