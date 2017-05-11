
<h4 class="nomargin">Messages
<!--    <span>Total Item (55)</span>-->
</h4>
<div class="table-responsive">
    <table class="table msglist mobile-table">
        <tbody>                  
            <?php 
            !$i=0;$display='';
            $numberOfUsersPerPage=config('project.mingle.message.numberOfUsersPerPage');                    
            ?>
            @foreach($allUsers as $allUser)
            <!--            conditions-->
            <?php 
             @$fromLastMessage=$messages->where('fromUserId',$allUser->id)->last();
             @$toLastMessage=$messages->where('toUserId',$allUser->id)->last();             
             @$lastMessage=($fromLastMessage->createdAt > $toLastMessage->createdAt)?$fromLastMessage:$toLastMessage; 
            ?>
            @if( ++$i > $numberOfUsersPerPage )
                {{ !$display='display:none' }}
            @endif
            <!--            conditions-->
            <tr onclick="window.location.href='{{URl('mingle/messages/'.encrypt($allUser->id))}}'" style="cursor: pointer;{{$display}}">
                <td class="col1" width='10%' data-title="Profile Pic">
                    <div class="thumb-pic">
                        <img src="{{ getUserProfileImage($allUser) }}" alt="Avtar" width="61" height="61">
                    </div>
                </td>
                <td class="col2" width='10%' data-title="Name">{{  @$allUser->first_name.' '.@$allUser->last_name }}
                    <span class="rating">
                        <img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt="">
                    </span>
                </td>
                <td class="col3" width='60%' data-title="Profile Choice">                    
                    {!! str_limit(@$lastMessage->text,150, '...') !!}
                </td>
                <td class="col4" width='20%' data-title="Visit Store">
                    <a href="#">                        
                        {{$lastMessage->datetime}}
                    </a>
                </td>
<!--                       <td class="col5" data-title="Action"><div class="dropdown"> <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Contact<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{URl('mingle/messages/'.encrypt($allUser->id))}}">Send Message</a></li>
                        </ul>
                    </div></td>-->
            </tr>
            @endforeach
        </tbody>
    </table>    
    @if($numberOfUsersPerPage<$allUsers->count())
        <a href="#" class="btn btn-block showmore" title="Show More">show more</a> 
    @endif
</div>
@push('scripts')
<script>          
    $('.showmore').click(function(){        
        for(i=0;i<{{$numberOfUsersPerPage}};i++){            
            if( $('.msglist tr:visible:last').next().length > 0 ){
                $('.msglist tr:visible:last').next().show();
            }            
            else{
                $('.showmore').remove();
                break;
            }
        }
        
    });
</script>
@endpush