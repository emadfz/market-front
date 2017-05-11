<div class="leftcol-bg">
    <a href="#" class="close-filter">Close</a>
    <ul class="product-nav">
        <li class="dashboard"><a href="{{route("sellerDashboard")}}" class="btn btn-primary" title="Dashboard">Dashboard</a></li>
        <li><a href="{{route("manageorder")}}" title="Manage Orders">Manage Orders<span>(5)</span></a>
            <ul class="submenu">
                <li><a href="sell-orderdetail.html" title="Order Detail">Order Detail</a></li>
                <li><a href="sell-invoice.html" title="View Invoice">View Invoice</a></li>
            </ul>
        </li>
        <li><a href="sell-returns.html" title="Manage Returns">Manage Returns<span>(3)</span></a>
            <ul class="submenu">
                <li><a href="sell-returndetail.html" title="Return Detail">Return Detail</a></li>
            </ul>
        </li>
        <li @if(Request::path()=='sell/product/index'){{'class=active'}}@endif>
            <a href="{{route("listingProduct")}}" title="Product Listing">
                Product Listing
            </a>
            <!-- <ul class="submenu">
                <li><a href="{{route("listingClassifiedProduct")}}" title="Classified Listing">Classified Listing</a></li>
            </ul> -->
        </li>
        <li>
            <a href="{{route("listingClassifiedProduct")}}" title="Classified Listing">
                Classified Listing
                <!-- <span>(5)</span> -->
            </a>
        </li>
        <li>
            <a href="sell-productlisting-preview.html" title="Preview Request">
                Preview Request
                <span>(5)</span>
            </a>
        </li>
        <li @if(Request::path()=='sell/bids'){{'class=active'}}@endif>
            <a href="{{url('/sell/bids')}}" title="Bids">
                Bids                                
                <span>({{App\Models\Auction::where('sellerId',\Auth::id())->groupBy('productId')->get()->count()}})</span>
            </a>
        </li>
        <li><a href="{{route("offers")}}" title="Offers">Offers<span>(5)</span></a></li>
        <li><a href="sell-mystore.html" title="My Store">My Store</a></li>
        <li><a href="sell-promotions.html" title="Promotions">Promotions</a></li>
    </ul>
</div>