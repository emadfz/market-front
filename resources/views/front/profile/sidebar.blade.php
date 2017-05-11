<div class="leftcol-bg">
    <a href="#" class="close-filter">Close</a>
    <ul class="product-nav">
        <li class="dashboard"><a href="{{route("getProfile")}}" class="btn btn-primary" title="Personal Information">Personal Information</a></li>
        
        @if(loggedinUserType() != 'Buyer')
        <li><a href="{{route("getBusiness")}}" title="Business Information">Business Information</a></li>
        @endif
        
        <li><a href="{{route("getAddress")}}" title="Address Book">Address Book</a></li>
        <li><a href="{{route("getFollowers")}}" title="Followers / Following">Followers / Following</a></li>
        <li><a href="{{route("getRating")}}" title="Rating & Feedbacks">Rating &amp; Feedbacks<span>(4)</span></a></li>
    </ul>
</div>