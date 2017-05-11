 <div class="col-lg-12 panel-product">
    	    <div class="title-line clearfix"><h3>Customers Who Bought This Item Also Bought<span><a href="#">View All</a></span></h3></div>
	        <div class="carousel-wrap">
       <div class="homepage-carousel">
        <ul class="bxcarousel_Customerbuy">
          @for($x=0 ; $x <10 ; $x++)
          <li>
            <div class="item">
                <div class="ribbon"><span>new</span></div>
                <figure class="img"><a href="product-detail.html"><img src="bootstrap/img/featured-img-1.jpg" width="174" height="174" alt=""></a></figure>
                <div class="item-detail">
                    <a href="product-detail.html" class="title">Angle &amp; Height Adjustable Rolling Laptop Desk ...</a>
                    <div class="price"><del><em><?php print(convert_currency( 30 , session()->get('currency_rate')));?></em></del><span><?php print(convert_currency( 70 , session()->get('currency_rate')));?></span></div>
                </div>
                <div class="item-footer">
                    <span>Free Shipping</span>
                    <span class="link">
                        <a href="#" title="Favorite" class="favorite active">Favorite</a> 
                        <a href="javascript:void(0);" title="Cart" class="cart active">Cart</a>
                    </span>
                </div>
            </div>
          </li>
           @endfor
      </ul>    
      </div>
       
		
	</div>
        </div>
