
<h4>Messages
<!--    <span>Total Item (55)</span>-->
</h4>
<div class="message-head">
    <div class="thumb-pic"><img src="{{getUserProfileImage(@$sentUser)}}" alt="Avtar" width="61" height="61"></div>
    <div class="user-activity">
        <div class="title">
            <span class="name">{{@$sentUser->first_name.@$sentUser->last_name}}</span>
            <span class="rating">
                <img src="{{ URL("/assets/front/img/star-medium.png" ) }}" alt="">
            </span>
            @if(isset($sentUser->MingleFollower->following_id) && $sentUser->MingleFollower->following_id != '')
                <span style="font-weight:normal;font-size:15px;">Follower</span>            
            @endif
        </div>
        <div class="userchoice">
            <a href="#" class="purple"><img src="{{ asset('/assets/front/img/userchoice-1.png')}}" alt="User Choice"></a>
            <a href="#" class="green"><img src="{{ asset('/assets/front/img/userchoice-2.png')}}" alt="User Choice"></a>
            <a href="#" class="orange"><img src="{{ asset('/assets/front/img/userchoice-3.png')}}" alt="User Choice"></a>
            <a href="#" class="red"><img src="{{ asset('/assets/front/img/userchoice-4.png')}}" alt="User Choice"></a>
        </div>
        <div class="btn-outer">
            <a href="#" class="cancel-link" title="Visit Store">Visit Store</a>
            @if(isset($sentUser->MingleFollowing->following_id) && $sentUser->MingleFollowing->following_id != '')
                <a href="javascript:void(0);" id="mingle_follow_{{$sentUser->id}}" data-bind="{{$sentUser->id}}" data-title="unfollowing" class="btn btn-primary btn-sm mingle_follow active" title="Unfollow">Unfollow</a>
            @else
                <a href="javascript:void(0);" id="mingle_follow_{{$sentUser->id}}" data-bind="{{$sentUser->id}}" data-title="following" class="btn btn-primary btn-sm mingle_follow" title="Follow">Follow</a>
            @endif            
        </div>
        <div class="head-content">            
            <div class="dropdown">
<!--                <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Contact<span class="caret"></span></a>-->
<!--                <ul class="dropdown-menu">
                    <li><a href="#">Delete Contact</a></li>
                    <li><a href="#">Block Contact</a></li>
                    <li><a href="#">Send New Invitation</a></li>
                </ul>-->
            </div>
        </div>
    </div>
</div>
<div class="message-outer">
    <div class="date">
        <div class="dropdown">
            <a href="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Conversion</a>
<!--            <ul class="dropdown-menu">
                <li><a href="#">Jump Back</a></li>
                <li><a href="#">6 Months</a></li>
                <li><a href="#">3 Months</a></li>
                <li><a href="#">30 Days</a></li>
                <li><a href="#">7 Days</a></li>
                <li><a href="#">Yesterday</a></li>
                <li><a href="#">Today</a></li>
            </ul>-->
        </div>
    </div>
    @if($messages->count()<1)
        <div class='norecord'>No records found</div>
    @endif
    
    <ul class="message-block custom-scrollbar">
       
                
                @foreach($messages as $message)                
                    @if($sentUser->id==$message->toUserId)
                        <li  class = "user">
                            <span class = "profile-icon">                                
                                <img src="{{getUserProfileImage($sentUser)}}" width="41" height="41" />                                                
                            </span>
                            <div class = "chatpanel clearfix">
                                <div class = "title">
                                    {{$message->username}}
                                </div>
                                <div class = "inner">
                                    @if( isset($message->greetingImage) && !empty($message->greetingImage) )
                                        <div class="image-msg">
                                                <img src="{{ $message->greetingImage }}" alt="Gift" width="689" height="334">
                                                @if( isset($message->text) && !empty($message->text) )
                                                    <div class="overly"><p>{!!@$message->text!!}</p></div>
                                                @endif
                                        </div>
                                    @else
                                        <p>
                                            {{$message->text}}
                                        </p>
                                    @endif
                                    
                                    <div class = "datetime">
                                         {{$message->datetime}}
                                    </div>
                                </div>
                            </div>
                        </li>
                    @else                    
                        <li>
                            <span class = "profile-icon">                                
                                <img src="{{getUserProfileImage(\Auth::user())}}" width="41" height="41" />                                
                            </span>
                            <div class = "chatpanel clearfix">
                                <div class = "title">
                                    {{$message->username}}
                                </div>
                                <div class = "inner">
                                    @if( isset($message->greetingImage) && !empty($message->greetingImage) )
                                         <div class="image-msg">
                                                <img src="{{ $message->greetingImage }}" alt="Gift" width="689" height="334">
                                                @if( isset($message->text) && !empty($message->text) )
                                                    <div class="overly"><p>{!!@$message->text!!}</p></div>
                                                @endif
                                        </div>
                                    @else
                                        <p>
                                            {{$message->text}}
                                        </p>
                                    @endif
                                    <div class = "datetime">
                                         {{$message->datetime}}
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
                
              
                
              
                
       
                                 
       
    </ul>
</div>


<div class="message-editor">
    <textarea id="message" data-userid="{{@$sentUser->id}}" cols="110" rows="5" class="form-control" ></textarea>
    <div class="form-btnblock clearfix text-right">
        <a href="#greeting_card" data-toggle="modal" class="btn btn-lightgray btn-sm" title="Send a Greeting Card">Send a Greeting Card</a>
        <a href="#" title="Cancel" class="cancel-link">Cancel</a>
        <a href="#" title="Send" id="sendbutton" data-message-type="plain"  class="sendbutton btn btn-primary btn-sm">send</a>
    </div>
</div>
@include('front.mingle.partials.greetingCard')
@push('scripts')
<script src="{{ asset('assets/front/js/socket.io.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/moment.min.js') }}" type="text/javascript"></script>
<script>
var socket = io.connect("http://<?php $_SERVER['HTTP_HOST'] ?>:3005");

//socket.emit('get-message');


$('.sendbutton').click(function () {    
        
        msg=$('#message').val();
        var data = new Object();
        if($(this).data('message-type')=="plain"){
                if($.trim(msg).length<1){
                    toastr.error('Please input Message!!');
                    return false;
                }
                data.greetingImage='';
                data.text = $('#message').val();
        }
        else{    
            str=$('#greeting_text').val();
            data.text = str.replace(/(?:\r\n|\r|\n)/g, '<br />');

                //data.text=$('#greeting_text').val();
                data.greetingImage=$('input[name="giftcard"]:checked').parent().parent().find('img').attr('src');
        }
        
        data.fromUserId = {{ \Auth::user()->id }}
        data.toUserId = {{@$sentUser->id}}        
        data.username = '<?php echo \Auth::user()->first_name . '-' . \Auth::user()->last_name; ?>';
        //data.datetime = Math.round(moment.utc(new Date()).valueOf()/ 1000);
        //data.datetime = Math.floor(Date.now() / 1000);
        data.datetime = moment.utc().format('h:mm, DD MMM YYYY');
        data.createdAt = Math.round(moment.utc(new Date()).valueOf()/ 1000);
        data.is_read = 'No';
        data.userImage = '{{getUserProfileImage(\Auth::user())}}';
        var dataString = JSON.stringify(data);

        var userData = $.parseJSON(dataString);
        html = '';          
        html='<li>'+
                '<span class = "profile-icon">'+
                '<img src = "{{getUserProfileImage(\Auth::user())}}" width = "41" height = "41" alt = "">'+
                '</span>'+
                '<div class="chatpanel clearfix">'+
                    '<div class="title">'+data.username+'</div>'+
                        '<div class="inner">';
                
                    if(data.greetingImage){
                        
                                html+='<div class="image-msg"><img src="'+data.greetingImage+'" alt="Gift" width="689" height="334">';
                                                if(data.text){
                                                    html+='<div class="overly"><p>'+data.text+'</p></div>';
                                                }
                               html+='</div>';
                    }
                    else{
                            html+='<p>'+data.text+'</p>';
                    }
                            
                            
                    html+='<div class="datetime">'+data.datetime+'</div>'+
                        '</div>'+
                    '</div>'+
              '</li>';
       $('.message-block .mCSB_container').append(html);
       $('#message').val('');
       socket.emit('send-message', userData);
        
       scrollToBottom();
       if($('.norecord')){
           $('.norecord').remove();
       }
    });


    $('#message').keypress(function (e) {
        if (e.which == 13) {
            $('#sendbutton').trigger('click');
        }
    });



    socket.on('message-<?php echo \Auth::user()->id; ?>', function (message,id) {
        socket.emit('update-msgflag',id);
    html = '';
    html='<li class = "user">'+
                '<span class = "profile-icon">'+                                                    
                    '<img src="'+message.userImage+'" width = "41" height = "41" alt = "">'+
                '</span>'+
                '<div class = "chatpanel clearfix">'+
                    '<div class = "title">'+message.username+'</div>'+
                    '<div class = "inner">';
                        if(message.greetingImage){

                                    html+='<div class="image-msg"><img src="'+message.greetingImage+'" alt="Gift" width="689" height="334">';
                                                    if(message.text){
                                                        html+='<div class="overly"><p>'+message.text+'</p></div>';
                                                    }
                                   html+='</div>';
                        }
                        else{
                                html+='<p>'+message.text+'</p>';
                        }
                        
                        html+='<div class = "datetime">'+message.datetime+'</div>'+
                    '</div>'+
                '</div>'+
            '</li>';

    $('.message-block .mCSB_container').append(html);
    scrollToBottom();


                            //alert(message);
                            //socket.emit('message-5',message);
                            //io.emit(channel + ':' + message.event, message.data);
                        });
                        
                        
    function scrollToBottom(){        
        $('.message-block').mCustomScrollbar("scrollTo","bottom",{
             scrollInertia:500,         
        });
    }
    
    $(document).ready(function(){    
        setTimeout(scrollToBottom,1000);    
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
  


</script>
@endpush