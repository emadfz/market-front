<div class="fullcolumn product-detail jewelaryDetail">
        <div class="row">
        <!--Leftsidebar start-->
    	<div class="col-md-5 product-leftcol clearfix">
        	<div class="productdata-gallery">
            	<div class="ribbon day"><span>1 Day Left</span></div>
                <div class="zoom"><span></span>Click to Zoom</div>
				<div class="clearfix">
                    <a href="{{ env("APP_URL").'/images/products/main/'.$product->Files[0]->path }}" class="jqzoom" rel='gal1'  title="{{ $product->name }}" >
                        <img src="{{ env("APP_URL").'/images/products/main/'.$product->Files[0]->path }}"  title="{{ $product->name }}">
                    </a>
				</div>
                <ul id="thumblist" class="clearfix" >
                    <?php $k=0;?>
                    @foreach ($product->Files as $file)
                                        <li><a <?php if($k == 0){ ?>class="zoomThumbActive" <?php }?> href='javascript:void(0);' rel="{gallery: 'gal1', smallimage: '{{ env("APP_URL").'/images/products/small/'.$file->path }}',largeimage: '{{ env("APP_URL").'/images/products/main/'.$file->path }}'}"><img src='{{ env("APP_URL").'/images/products/small/'.$file->path }}'></a></li>
                        <?php $k++;?>                    
                    @endforeach                    
				</ul>
            </div>
        </div>
        <!--leftsidebar close-->
        <!--Rightsidebar Start-->
        <div class="col-md-7 padd-leftnone product-rightcol">
     		@include('front.products.partials.title')<span>SKU:2052652</span>
            <p>FREE FedEx&reg; Shipping on every order. Delivery time varies by the diamond and setting you select.</p>
            <p>Order in the next 10 hours 55 minutes for free delivery on Friday, October 21.Order in the next 10 hours 55 minutes for 
free delivery on Friday, October 21.</p>
            <!--Social Connect start-->
            @include('front.products.partials.social_share')
            <!--Social Connect End-->
            <!--Product code and Seller Start-->
                    @include('front.products.partials.review_feedback_stats')
                    <!--Product code and Seller End-->
            <!--Product Specifiaction Start-->
            <div class="product-spec clearfix">
            	<div class="leftcol">
                	<p class="medium">20 Available 50 Sold<span>899 Viewed per day</span></p>
                    <!--<div class="small-semibold pull-right"><span class="normal">Item Condition:</span> New</div> -->
                    <div class="filter">
                        <!--<div class="form-group clearfix">
                            <label class="medium">Color:</label>
                            <div class="selectbox width125"> 
                            <select class="selectpicker">
                                <option>Select Color</option>
                                <option value="Red">Red</option>
                                <option value="Green">Green</option>
                            </select>
                        </div>
                        </div> -->
                        <?php
                            $keys=array_keys($attributes);
                            for($i=0;$i<count($keys);$i++){ ?>
                        <div class="form-group clearfix">
                            <label class="medium">{{$keys[$i]}}:</label>
                            <div class="selectbox width125"> 
                            
                            <select name='{{$keys[$i]}}_attribute' id='{{$keys[$i]}}_attribute' class="selectpicker" onchange="change_product('{{$keys[$i]}}_attribute');">
                                <?php 
                                for($j=0;$j<count($attributes[$keys[$i]]); $j++){?>
                                    <option selected="" value="{{$attributes[$keys[$i]][$j]['sku_id'].'-'.$attributes[$keys[$i]][$j]['price']}}">{{$attributes[$keys[$i]][$j]['values']}}</option>
                                <?php
                                }
                                ?>
                    </select>
                            </div>
                            <!--<a href="#" class="bluetext" title="Size Chart">Size Chart</a> -->
                        </div>
                            <?php } ?>
                       <!-- <div class="form-group clearfix">
                            <label class="medium">Quantity:</label>
                            <input type="text" name="color" class="form-control width50 pull-left">
                            <a href="#bulkbuy-modal" data-toggle="modal" class="bluetext" title="View Bulk Buy Option">View Bulk Buy Option</a>
                        </div> -->
                    </div>
                	<!--Product Price and Button Start-->            
            		<div class="price-btn">
            			<div class="price-show">
                    	<label class="medium nomargin">Price:</label>
                        <del>$650</del>
                        <span id='final_price'><?php print(convert_currency( $product->base_price , session()->get('currency_rate')));?></span>
                        <!--<small>(10% Off)</small> -->
                    </div>
                		<div class="button-col">
            <span id="add_cart_btn"><a href="#" class="btn btn-primary" title="Add to cart" onclick="addtocartSku({{$product->productSkusVariantAttribute[0]->id}},1);">Add to cart</a></span>
                        <a href="#setpreview-modal" data-toggle="modal" class="btn btn-primary" title="Set A Preview">Set A Preview</a>                                        
                        <a href="shoppingcart.html" class="btn btn-success" title="Make An Offer">Buy Now</a>
                	</div>
            		</div>
            		<!--Product Price and Button End-->               
                </div>
                <div class="rightcol">
                	<div class="flexbox">
                    	<div class="product-spec-btn">
                        	<div class="button"><div class="inner">Warranty<span>1 Year Warranty</span></div></div>
                            <div class="button"><div class="inner">Returns<span>30 Days Returns</span></div></div>
                            <div class="button"><div class="inner">Shipping<span>Free Shipping</span></div></div>
                        </div>
                    	<div class="product-spec-logo">
                        	<img src="{{asset('assets/front/img/gia-logo.png')}}" alt="GIA Logo" width="96" height="94">
                        </div>
                    </div>    
                    <ul class="print-mail clearfix">
                    	<li class="print"><a href="#" title="Print"><span></span>print</a></li>
                        <li class="email"><a href="#" title="Email"><span></span>email</a></li>
                        <li class="flag"><a href="#" title="Report"><span></span>report</a></li>
                	</ul>    
                </div>
            </div>
            <!--Product Specifiaction End-->
            <!--Product Buy,Return,Shipping Start-->               
                    <div class="buyway">
                        @include('front.products.partials.shipping_option')
                        @include('front.products.partials.payments')
                        @include('front.products.partials.returns')
                    </div>
                    <!--Product Buy,Return,Shipping End-->	
        </div>
         <!--Rightsidebar Close-->
     </div>
     <hr>
     <div class="row ">
     	<!--Product description Tab start-->
        <div class="col-lg-12 product-desc">
        	 <!-- Nav tabs -->
              <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tabproduct" role="tab" data-toggle="tab">Product Details</a></li>
                <li role="presentation"><a href="#tabpayment" role="tab" data-toggle="tab">Payment</a></li>
                <li role="presentation"><a href="#tabshipreturn" role="tab" data-toggle="tab">Shipping &amp; Return</a></li>
                <li role="presentation"><a href="#tabreview" role="tab" data-toggle="tab">Product Reviews</a></li>
              </ul>
              <!-- Tab panes -->
              <div class="tab-content">
                @include('front.products.partials.tabs.product_details')
                <div role="tabpanel" class="tab-pane" id="tabpayment">2</div>
                <div role="tabpanel" class="tab-pane" id="tabshipreturn">3</div>
                <div role="tabpanel" class="tab-pane" id="tabreview">
                	<!--Product review start-->
       				<div class="row">
        			<div class="col-md-8 productreview-outer">
                    <ul class="ratingtype">
                    	<li>Would recommend<span><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"></span></li>
                    	<li>Good value<span><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"></span></li>
                    	<li>Good quality<span><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"></span></li>
                    </ul>    
                    <h3>Write Review</h3>
                    <div class="form-group">
                    	
                        <div class="clearfix text-right mrg-top10">
            				<a href="#" class="cancel-link btn-sm" title="Cancel">Cancel</a>
            				<input type="submit" title="Submit" class="btn btn-primary btn-sm" value="Submit">
			            </div>
                    </div>
                    <div class="reviewblcok">
                    	<div class="review-tophead text-right">
                        	<div class="ratingouter">
                            	<img src="bootstrap/img/star-medium.png" alt="" width="83" height="14">
                                <span>5 Out of 5 stars based on <a href="#">3 reviews</a></span>
                            </div>
                        </div>
                        <div class="media"> 
                        	<div class="media-left"><img class="media-object" src="bootstrap/img/fourms-avtar.jpg" alt="Avtar" width="61" height="61"></div> 
                        	<div class="media-body">
                                <div class="media-heading"><a href="mingle-forums-addcomment.html">John Doe,</a>
                                	<span class="date">Dec 11, 2015. 11:45,</span>
                                	<span class="replies">77,545 Replies</span>
                                    <div class="rating"><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"><span>5.0 Stars</span></div>
                                </div>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p> 
                        		<ul class="fourms-result">
                                    <li class="recommended">Recommended:<span>Yes</span></li>
                                    <li class="active"><a href="#" title="Like"><i class="likes"></i>Like<b>(1)</b></a></li>
                                    <li class="active"><a href="#" title="Dislike"><i class="dislikes"></i>Dislike<b>(0)</b></a></li>
                                    <li class="active"><a href="#" title="Flag"><i class="flag"></i></a></li>
                            	</ul> 
                            </div>
                            
                        </div> 
            			<div class="media"> 
                        	<div class="media-left"><img class="media-object" src="bootstrap/img/fourms-avtar.jpg" alt="Avtar" width="61" height="61"></div> 
                        	<div class="media-body">
                                <div class="media-heading"><a href="#">John Doe,</a>
                                	<span class="date">Dec 11, 2015. 11:45,</span>
                                	<span class="replies">77,545 Replies</span>
                                    <div class="rating"><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"><span>5.0 Stars</span></div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="media"> 
                        	<div class="media-left"><img class="media-object" src="bootstrap/img/fourms-avtar.jpg" alt="Avtar" width="61" height="61"></div> 
                        	<div class="media-body">
                                <div class="media-heading"><a href="mingle-forums-addcomment.html">John Doe,</a>
                                	<span class="date">Dec 11, 2015. 11:45,</span>
                                	<span class="replies">77,545 Replies</span>
                                    <div class="rating"><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"><span>5.0 Stars</span></div>
                                </div>
                                
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p> 
                        		<ul class="fourms-result">
                                    <li class="recommended">Recommended:<span>Yes</span></li>
                                    <li><a href="#" title="Like"><i class="likes"></i>Like<b>(1)</b></a></li>
                                    <li><a href="#" title="Dislike"><i class="dislikes"></i>Dislike<b>(0)</b></a></li>
                                    <li><a href="#" title="Flag"><i class="flag"></i></a></li>
                            	</ul> 
                            </div>
                            
                        </div>
                        <a href="#" class="btn btn-block showmore" title="Show More Review">Show More Review</a>
                    </div>
            			
            		</div>
            		<div class="col-md-4">
                    	<div class="reviewsummary">
                        	<h3>Reviews Summary</h3>
                    		<div class="viewreview-outer mrg-bott20 clearfix">
                              	 <div class="rating-count">
                                        	<h6>5.2</h6>
                                            <div class="rating"><img src="bootstrap/img/star-medium.png" alt="" width="83" height="14"></div>
                                        	<p>average based on 7 ratings</p>
                                        </div>
                                 <div class="ratingpoint-outer">
                                        	<div class="ratingpoint">
                                        	<i class="star-display"></i>
                                        	<small class="rating-display">5</small>
                                        	<div class="progress">
  												<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width:70%;">7</div>
											</div>
                                        </div>
                                        	<div class="ratingpoint">
                                        	<i class="star-display"></i>
                                        	<small class="rating-display">4</small>
                                        	<div class="progress">
  												<div class="progress-bar" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width:20%;">2</div>
											</div>
                                        </div>
                                        	<div class="ratingpoint">
                                        	<i class="star-display"></i>
                                        	<small class="rating-display">3</small>
                                        	<div class="progress">
  												<div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width:30%;">3</div>
											</div>
                                        </div>
                                       		<div class="ratingpoint">
                                        	<i class="star-display"></i>
                                        	<small class="rating-display">2</small>
                                        	<div class="progress">
  												<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%;">0</div>
											</div>
                                        </div>
                                        	<div class="ratingpoint">
                                        	<i class="star-display"></i>
                                        	<small class="rating-display">1</small>
                                        	<div class="progress">
  												<div class="progress-bar" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width:10%;">1</div>
											</div>
                                        </div>
                                        </div>
                            </div>
                            <div class="viewreview-outer clearfix text-center">
                                 <div class="circle-outer">
                                                    <div class="c100 p12 small green">
                                                        <span>12%</span>
                                                        <div class="slice">
                                                            <div class="bar"></div>
                                                            <div class="fill"></div>
                                                        </div>
                                                    </div>
                                                    <p>Would recommend</p>
                                                </div>
                                 <div class="circle-outer">
                                                <div class="c100 p99 small dark">
                                                    <span>99%</span>
                                                    <div class="slice">
                                                        <div class="bar"></div>
                                                        <div class="fill"></div>
                                                    </div>
                                                </div>
                                                    <p>Good value</p>
                                                </div>
                                 <div class="circle-outer">
                                                <div class="c100 p100 small orange">
                                                    <span>100%</span>
                                                    <div class="slice">
                                                        <div class="bar"></div>
                                                        <div class="fill"></div>
                                                    </div>
                                                </div>
                                                     <p>Good quality</p>
                                                </div>
                            </div>
                        </div>
                    </div>
        		</div>				
        			<!--Product review end-->
                </div>
              </div>
        </div>
     	
      <!--Product description Tab Close-->
                <!--Related Products start-->
                @include('front.products.partials.slider.related_products')
                <!--Related Products End-->
                <!--Customer Bought Products start-->
                @include('front.products.partials.slider.customer_also_buy')
                <!--Customer Bought Products End-->
                <!--Your Recently Viewed Product start-->
                @include('front.products.partials.slider.recently_viewed')
                <!--Your Recently Viewed Product End-->
                <!--Featured Products start-->
                @include('front.products.partials.slider.featured_products')
                <!--Featured Products End-->
            </div>        
        </div>

@push('scripts')
<script src="{{ asset('assets/front/js/jquery.jqzoom-core.js') }}"></script> 
<script type="text/javascript">
$(document).ready(function() {
	$('.jqzoom').jqzoom({
            zoomType: 'innerzoom',//innerzoom',
            preloadImages: true,
            alwaysOn:false,
			zoomWidth: 470,
//            //zoomWindow  default width
            zoomHeight: 450,
//			// xOffset: 160,
        });
});


</script>

@endpush