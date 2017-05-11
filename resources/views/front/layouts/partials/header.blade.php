<?php 
use App\Models\occasions;
use App\Models\Currency;
$occs=   new Occasions;
$occs = $occs->getOccasions(\Carbon\Carbon::now());
$occs_count =$occs->count();

$currency = Currency::orderBy('created_at', 'desc')->first();
$currencies = json_decode($currency->json_data);
$currency = session()->get('currency');
?>

<header class="header">
    <!-- Top header start -->
    <div class="top-header">  				
        <div class="container">
            <div class="row">
                <!-- start navbar main -->
                <nav class="navbar navbar-default">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <ul class="navbar-brand top-leftnav">

                            @if(!isLoggedin())
                            <li class="line"><a href="javascript:void(0)" class="signinModal" id="signInId" title="Signin" data-toggle="modal" data-backdrop="static" data-keyboard="false">{{trans('form.auth.signin')}}<span class="sr-only">(current)</span></a></li>
                                <li><a href="{{route('individualRegister')}}" title="Register">Register</a></li>
                            @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Buy">Hi {{ auth()->user()->first_name}}<span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="" title="My Account">My Account</a></li>
                                    <li><a href="{{route("listingProduct")}}" title="My Activity">My Activity</a></li>
                                    <li><a href="{{route("getProfile")}}" title="My Profile">My Profile</a></li>
                                    <li><a href="" title="My Settings">My Settings</a></li>
                                    <li><a href="{{route("logoutUser")}}" title="Logout">Logout</a></li>
                                </ul>
                            </li>
                            @endif
                        </ul>

                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button> 
                        <div class="visible-xs language">
                            <div class="selectbox">
                                <select class="selectpicker">
                                    <option>EN</option>
                                    <option>FR</option>
                                </select>
                            </div>
                        </div>
                        <div class="language currancy visible-xs">
                            <div class="selectbox">
                                <select class="selectpicker">
                                @foreach($currencies->rates as $countryCode=>$price)
                                    <option>{{$countryCode}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div> 									
                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-left">
                            <!--<li class="active line"><a href="#" title="Signin">Login<span class="sr-only">(current)</span></a></li>
                            <li><a href="#" title="Register">Register</a></li>  -->
                            <li class="dropdown buy">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Buy">Buy <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#" title="Help Centre for Buyers">Help Centre for Buyers</a></li>
                                    <li><a href="#" title="Buy Bulk">Buy Bulk</a></li>
                                    <li><a href="#" title="FAQ">FAQ</a></li>  											
                                    <li><a href="#" title="Advertisement">Advertisement</a></li>
                                </ul>
                            </li>
                            <li class="dropdown sell">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Sell">Sell <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#" title="Help Centre for Sellers">Help Centre for Sellers</a></li>
                                    <li><a href="#" title="FAQ">FAQ</a></li>
                                    <li><a href="#" title="Others">Others</a></li>  											
                                </ul>
                            </li>
                            <li class="dropdown mingle">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Mingle">Mingle <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    @if(isMingleSync())
                                    <li><a href="{{route("getConnect")}}" title="Get Connected">Get Connected</a></li>
                                    @else
                                    <li><a href="{{route("mingleSync")}}" title="Get Connected">Get Connected</a></li>
                                    @endif
                                    <li><a href="{{route("forum")}}" title="Forum">Forums</a></li>
                                    <li><a href="#" title="About Mingle">About Mingle</a></li>  											
                                    <li><a href="#" title="Terms of Use">Terms of use</a></li>
                                    <li><a href="#" title="Others">Others</a></li>
                                </ul>
                            </li>
                            <li class="dropdown help">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Help">Help <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#" title="About Us">About Us</a></li>
                                    <li><a href="#" title="FAQ">FAQ</a></li>
                                    <li><a href="#" title="New User Guide">New User Guide</a></li>                        
                                    <li><a href="#" title="Membership Benefits">Membership Benefits</a></li>
                                    <li><a href="#" title="Live Chat">Live Chat</a></li>
                                    <li><a href="#" title="Contact">Contact</a></li>
                                </ul>
                            </li>
                        </ul>
                        @section('occ_img')
                            <?php $x = 1; $y = 0;?>

                            @foreach($occs as $occ) @if(count($occ->files))
                            <?php $y++; ?> 
                            @endif @endforeach
                            @foreach($occs as $occ)
                            @if(count($occ->files))
                                <a href="#" id="occ_{{$x}}" @if($x==1 && $y > 1) style="display: none" @endif  class="head-advertise count"><img src="{{getDocumentPath($occ->files->first()->path)}}" alt="" height="50" width="254"></a>
                            {{!$x++}}
                            @endif
                            @endforeach
                            
                        @endsection

                        @yield('occ_img')
                        <ul class="nav navbar-nav navbar-right">
                            <!-- <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">My Account <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">My Profile</a></li>
                                    <li><a href="#">My Activities</a></li>
                                    <li><a href="#">My Dashboard</a></li>  											
                                    <li><a href="#">My Settings</a></li>
                                    <li><a href="#">Sign Out</a></li>
                                </ul>

                            </li><li class="dropdown compare">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Compare">Compare (0) <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Product 1</a></li>
                                    <li><a href="#">Product 2</a></li>                       
                                </ul>
                            </li> -->
                            

                            <li class="dropdown compare">
                                
                                
                                @if( count(request()->cookie('compare'))>0 )
                                    <?php                                               
                                        $cookie_cats=[];
                                        $compare_cookie=request()->cookie('compare');                                        
                                        $cookie_cats=App\Models\Category::whereIn('id', array_keys($compare_cookie) )->get();                                        
                                        
                                    ?>
                                    <a href="{{ URL('/getComparedProduct') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Compare">Compare<span class="caret"></span></a>
                                    @if(isset($cookie_cats) && !empty($cookie_cats) && $cookie_cats->count()>0)
                                        <ul class="dropdown-menu">
                                            @foreach($cookie_cats as $cookie_cat )
                                                <?php                                                
                                                    $products=App\Models\Product::whereIn('id',$compare_cookie[$cookie_cat->id])->get()->toArray();
                                                    $url=URL('/compare/'.$cookie_cat->category_slug.'/'.implode('/',array_column($products,'product_slug')));
                                                ?>                                            
                                                <li><a href="{{ $url }}">{{$cookie_cat->text}}({{count($compare_cookie[$cookie_cat->id])}})</a></li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endif

                            </li>
                            <!-- <li><a href="#" class="help"></a></li> -->
                           <!-- <li><a href="#" class="nav-favourite" title="Favourite">Favourite</a></li>-->
                            <li><a href="{{route("favorite")}}" <?php if(count(Cart::instance('favorite')->content()) > 0){ ?> class="nav-favourite active" <?php } else { ?> class="nav-favourite" <?php } ?> title="Favourite" id="favorite"><span></span></a></li>
                            <li><a href="#" class="nav-email" title="Email"><span></span>(4)</a></li>	
                           
                            <li><a href="{{route("cart")}}" class="top-cart" title="Shopping Cart">
                       <!--<span class="count orange">3</span> <i class="shopping-cart" aria-hidden="true"></i> -->
                                <span></span><strong class="cartcontent">(<?php echo count(Cart::instance('default')->content()); ?>)</strong></a>
                            </li>
                            <li class="dropdown language hidden-xs">
                                <div class="selectbox">
                                    <select class="selectpicker">
                                        <option>EN</option>
                                        <option>FR</option>
                                    </select>
                                </div>
                            </li>
                            <li class="dropdown language currancy hidden-xs">
                                <div class="selectbox">
                                    <select class="selectpicker" onChange="window.location.href='{{env('APP_URL')}}/'+'currency-change/'+this.options[this.selectedIndex].id+'/'+this.options[this.selectedIndex].value;" value="GO">
                                    @foreach($currencies->rates as $countryCode=>$price)
                                         <option value="{{$price}}" id="{{$countryCode}}" @if($currency == $countryCode) active @endif>{{$countryCode}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </li>									
                        </ul>
                    </div><!-- /.navbar-collapse <--></-->
                </nav><!-- end navbar main --> 
            </div>
        </div>
    </div>
    <!-- Top header end -->
    <!-- second header start -->
    <div class="serch-sec stickynav">
        <div class="container">
            <div class="row">
                <nav class="navbar navbar-default">
                    <h1 class="navbar-brand logo"><a href="{{route('homepage')}}" title="Inspree"><img src="{{asset('assets/front/img/inspree-logo.png')}}" width="94" height="31" alt="Inspree"></a></h1>

                    <ul class="nav navbar-nav megamenu">
                        <li><a href="{{URL('occasions')}}" title="Shop by Occasion">Shop by<br>Occasion <span class="caret"></span></a></li>
                        <li><a href="{{URL('categories')}}" title="Shop by Category">Shop by<br>Category <span class="caret"></span></a></li>
                    </ul>
                    <div class="search-bar">
                        <input type="text" name="product search" class="form-control" placeholder="Search for a product, Brand or category">
                        <div class="selectbox">
                            <select class="selectpicker">
                                <option selected="" value="All Category">All Category</option>
                                <option value="Jewellery">Jewellery</option>
                                <option value="Real Estate">Real Estate</option>
                                <option value="Fashion">Fashion</option>
                                <option value="Clothing">Clothing</option>
                                <option value="Automobile">Automobile</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Fitness &amp; Sports">Fitness &amp; Sports</option>
                            </select>
                        </div>
                        <a class="btn btn-primary searchbtn" title="Search">Search</a>
                        <!--Advanced Search Start-->
                        <div class="dropdown advancelink">
                            <a href="#advanced" title="Advanced" data-toggle="modal" data-backdrop="static" data-keyboard="false">Advanced</a>
                        </div>
                        <!--Advanced Search End-->
                    </div>

                </nav>
            </div>  					
        </div>
    </div>
    <!-- second header End -->
</header>