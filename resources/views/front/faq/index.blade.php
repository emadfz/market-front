@extends('front.layouts.app')

@section('content')
    <!-- Start container here -->
    <section class="content">
        <div class="container">
            <!--breadcrumb Start-->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li><a href="index.html">Home</a></li>
                        <li class="active">My Account</li>
                    </ul>
                </div>
            </div>
            <!--breadcrumb Start-->
            <h2>Help &amp; Support </h2>
            <div class="account-page"> <a href="#" id="productnav" class="btn btn-info visible-xs-inline-block">Filter Option</a>
                <div class="widecol-bg clearfix">
                    <!--Leftside Start -->
                    <div class="leftcol-bg"> <a href="#" class="close-filter">Close</a>
                        <div class="searchouter">
                            <div class="input-search">
                                <input type="text" class="form-control padd-right35" placeholder="Search">
                                <a href="#" class="search-icon"></a> </div>
                        </div>
                        <ul class="product-nav">

                            @foreach($topics as $topic)

                            <li class="account-querie"><a href="{{route('helpcenter_single', $topic->id)}}" title="Top Queries"><span></span><small>{{$topic->topic_name}}</small></a></li>

                            @endforeach
                             <li class="account-querie"><a href="{{route('termscond')}}" title="Top Queries"><span></span><small>Terms & Conditions</small></a></li>
                        </ul>
                    </div>
                    <!--Leftside End -->
                    <!--Rightside Start -->
                    <div class="rightcol-bg clearfix">
                        <div class="inner-rightcol">
                            <div class="rightcol-outer nopadding">
                                <div class="account-banner">
                                    <img src="{{asset('assets/front/img/account-banner.jpg')}}" alt="Banner">
                                    <a href="#" class="btn btn-primary btn-sm" title="Start Selling">Start Selling</a>
                                </div>
                            </div>
                            <div class="rightcol-outer">
                                <div class="account-keypoint">
                                    <h3>1.Getting Ready to Sell</h3>
                                    <ul class="keylist">
                                        <li><span></span>Select the item you want to sell</li>
                                        <li><span></span>Take at least 4 photos of your item from different angles</li>
                                    </ul>
                                </div>
                                <div class="account-keypoint">
                                    <h3>2.Creating your Listing</h3>
                                    <ul class="keylist">
                                        <li><span></span>Click the sell link at the top of any Devanche page</li>
                                        <li><span></span>Type the name of the item you want to sell using descriptive keywords to help others find your listing</li>
                                        <li><span></span>Select the condition that best matches your item</li>
                                        <li><span></span>Upload the photos you took earlier</li>
                                        <li><span></span>Enter extra details about your item in the description box to highlight unique features or defects</li>
                                        <li><span></span>Consider donating to your favorite charity – it'll help your listing sell and for a higher final selling price</li>
                                    </ul>
                                </div>
                                <div class="account-keypoint">
                                    <h3>3.Pricing and Listing your item</h3>
                                    <ul class="keylist">
                                        <li><span></span>A recommendation on preferred listing format (Auction or Fixed Price) and pricing will be provided</li>
                                        <li><span></span>A recommendation on shipping service and cost will be provided</li>
                                        <li><span></span>Review your listing—then click "List it" and you're done.</li>
                                    </ul>
                                </div>
                                <div class="quicklink-box clearfix">
                                    <h3>Quick sell links</h3>
                                    <p>Select an item to sell</p>
                                    <ul class="itemlist clearfix">
                                        <li class="col-md-6">
                                            <i>Smartphones</i>
                                            <h5>Smartphones</h5>
                                            <p>See how much your phone flips for—we're sure it'll sell on inSpree.</p>
                                        </li>
                                        <li class="col-md-6">
                                            <i class="camera">Digital cameras</i>
                                            <h5>Digital cameras</h5>
                                            <p>Whether you've got a point and shoot or a digital SLR, you can sell it on inSpree.</p>
                                        </li>
                                        <li class="col-md-6">
                                            <i class="tablet">Tablets</i>
                                            <h5>Tablets</h5>
                                            <p>Simply select your brand and model of tablet, see its current value, and list it.</p>
                                        </li>
                                        <li class="col-md-6">
                                            <i class="mp3">MP3 players and headphones</i>
                                            <h5>MP3 players and headphones</h5>
                                            <p>Got headphones, a digital media player, or a satellite radio? List and sell it here.</p>
                                        </li>
                                        <li class="col-md-6">
                                            <i class="game">Video game consoles</i>
                                            <h5>Video game consoles</h5>
                                            <p>Whether it's a PS4, xBox, or another brand, find out the current value and list it.</p>
                                        </li>
                                        <li class="col-md-6">
                                            <i class="other">Others</i>
                                            <h5>Others</h5>
                                            <p>With over 100 million buyers, eBay is the best place to sell online and make money.</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Rightside End -->
                </div>
            </div>
        </div>
    </section>
    <!-- End container here -->


@endsection