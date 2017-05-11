<div class="col-lg-12 panel-product">
    <div class="title-line clearfix"><h3>Featured Products<span><a href="#">View All</a></span></h3></div>

    <div class="carousel-wrap">
        <div class="homepage-carousel">
        <!-- featured item carousel-->
        <ul class="bxcarousel_Featured">
            <?php $i = 1; ?>
            @foreach($sliders['Featured_Products'] as $slide)
            <li>  <div class="item" id="item{{$i}}">
                <div class="ribbon"><span>new</span></div>
                <a href="javascript:void(0);" onclick="removeproduct_Featured('item{{$i}}');" class="product-close" title="Delete"></a>
                <figure class="img">
                    <a href="{{ route("productSlugUrl",[$slide->product_slug]) }}"><img src="{{checkImageExists('/images/products/main/'.@$slide->Files[0]->path)}}" width="174" height="174" alt=""></a>
                </figure>
                <div class="item-detail">
                    <?php
                    $length = 18;
                    $string = $slide->name;
                    if (strlen($slide->name) > $length) {
                        $append = "...";
                        $string = wordwrap($slide->name, $length);
                        $string = explode("\n", $string, 2);
                        $string = $string[0] . $append;
                    }
                    ?>
                    <a href="#" class="title">{{@$string}}</a>
                    <div class="price"><del><em><?php print(convert_currency( 70 , session()->get('currency_rate')));?></em></del><span><?php print(convert_currency( $slide->base_price , session()->get('currency')));?></span></div>
                </div>
                <div class="item-footer">
                    <span>Free Shipping</span>
                    <span class="link">
                        <a href="#" title="Favorite" class="favorite active">Favorite</a> 
                        <a href="#" title="Cart" class="cart">Cart</a>
                    </span>
                </div>
                </div></li>
            <?php $i++; ?>
            @endforeach                  
        </ul>
        </div>
        <!-- End featured item carousel-->
    </div>


    <!--    <div class="carousel-wrap mrg-bottom">
             featured item carousel
            <div id="featuredItem" class="owl-carousel owl-theme homepage-carousel">
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <figure class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-1.jpg')}}" width="174" height="174" alt=""></a></figure>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite active">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <div class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-2.jpg')}}" width="174" height="174" alt=""></a></div>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <div class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-3.jpg')}}" width="174" height="174" alt=""></a></div>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <div class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-4.jpg')}}" width="174" height="174" alt=""></a></div>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <div class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-5.jpg')}}" width="174" height="174" alt=""></a></div>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <div class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-6.jpg')}}" width="174" height="174" alt=""></a></div>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
                <div class="item">
                    <a href="#" class="product-close" title="Delete">Delete</a>
                    <div class="img"><a href="product-detail.html"><img src="{{asset('assets/front/img/featured-img-1.jpg')}}" width="174" height="174" alt=""></a></div>
                    <div class="item-detail">
                        <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                        <div class="price"><del><em>70.95</em></del><span>$41.95</span></div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="customNavigation">
                <a class="btn prev"></a>
                <a class="btn next"></a>
            </div>
             End featured item carousel
        </div>-->
</div>
