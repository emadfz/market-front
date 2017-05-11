<div class="leftcol-bg"> <a href="#" class="close-filter">Close</a>
  <ul class="product-nav">
    <li class="dashboard"><a href="{{route("buyerDashboard")}}" class="btn btn-primary" title="Dashboard">Dashboard</a></li>
    <li @if(Request::path()=='buy/manageorder'){{'class=active'}}@endif>
        <a href="{{route("manageorder")}}" title="Order History">
            Order History
            <span>(5)</span>
        </a>
      <ul class="submenu">
        <li><a href="buy-orderinfo.html" title="Order Information">Order Information</a></li>
        <li><a href="buy-invoice.html" title="View Invoice">View Invoice</a></li>
      </ul>
    </li>
    <li><a href="buy-returnhistory.html" title="Return History">Return History<span>(3)</span></a>
      <ul class="submenu">
        <li><a href="buy-returndetail.html" title="Return Detail">Return Detail</a></li>
      </ul>
    </li>    
    <li @if(Request::path()=='buy/bids'){{'class=active'}}@endif>
         <a href="{{URL('/buy/bids')}}" title="Placed Bids">
            Placed Bids
            <span>({{App\Models\Auction::where('user_id',\Auth::id())->groupBy('productId')->get()->count()}})</span>
        </a>
    </li>
    <li @if(Request::path()=='buy/buyerOffers'){{'class=active'}}@endif>
         <a href="{{route("buyerOffers")}}" title="Placed Offers">
            Placed Offers
            <span>(5)</span>
        </a>
  </li>
    <li><a href="buy-review.html" title="Received Review ">Received Review </a></li>
    <li><a href="buy-preview.html" title="Preview Request">Preview Request<span>(5)</span></a></li>
    <li><a href="{{route("advertisements")}}" title="Purchased Ads">Purchased Ads</a></li>
    <li><a href="buy-wishlist.html" title="Wish List">Wish List</a></li>
    <li><a href="buy-requested.html" title="Product Requested">Product Requested</a></li>
  </ul>
</div>
