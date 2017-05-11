<ul class="product-nav">
    <li <?php if (Request::is('*pending*') == 1) { ?>class="active" <?php }?>><a href="{{route('getMingleType',['pending'])}}" title="Received Invitation">Received Invitation<span>({{$pendingCount}})</span></a></li>    
    <li <?php if (Request::is('*messages*') == 1) { ?>class="active" <?php }?>><a href="{{route('messages')}}" title="Message">Message<span>({{ \App\Models\MingleMessage::where('is_read','No')->where('toUserId',\Auth::id())->count() }})</span></a></li>
    <li <?php if (Request::is('*sent*') == 1) { ?>class="active" <?php }?>><a href="{{route('getMingleType',['sent'])}}" title="Sent Invitation">Sent Invitation<span></span></a></li>
    <li <?php if (Request::is('*accept*') == 1) { ?>class="active" <?php }?>><a href="{{route('getMingleType',['accept'])}}" title="My Connections">My Connections<span></span></a></li>
    <li <?php if (Request::is('*block*') == 1) { ?>class="active" <?php }?>><a href="{{route('getMingleType',['block'])}}" title="Block Contacts">Block Contacts<span></span></a></li>
    <li <?php if (Request::is('*archive*') == 1) { ?>class="active" <?php }?>><a href="{{route('getMingleType',['archive'])}}" title="Archive Contacts">Archive Contacts<span></span></a></li>
</ul>