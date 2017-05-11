<h2>My Activity</h2>
<!-- Nav tabs Start-->

<ul class="myactivity-tab clearfix">

    <li <?php if (Request::is('*buy*') == 1) { ?>class="active" <?php }?>><a href="{{route("buyerDashboard")}}" title="Buy">Buy</a></li>
    <li <?php if (Request::is('*sell*') == 1) { ?>class="active" <?php }?> ><a href="{{route("listingProduct")}}" title="Sell">Sell</a></li>
    <li <?php if (Request::is('*mingle*') == 1) { ?>class="active" <?php }?>><a href="{{route("getConnect")}}" title="Mingle">Mingle</a></li>
    <li><a href="#" title="Communications">Communications</a></li>
    <li <?php if (Request::is('*profile*') == 1) { ?>class="active" <?php }?>><a href="{{route("getProfile")}}" title="My Profile">My Profile</a></li>
    <li><a href="#" title="Settings">Settings</a></li>
</ul>
<!-- Nav tabs End-->
