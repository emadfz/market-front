<div class="fullcolumn product-detail">
            <div class="row">
                <!--Leftsidebar start-->
                @include('front.products.partials.slider.slideshow')
                <!--leftsidebar close-->
                <!--Rightsidebar Start-->
                <div class="col-md-7 padd-leftnone product-rightcol">
                    @include('front.products.partials.title')
                    <!--Social Connect start-->
                    @include('front.products.partials.social_share')
                    <!--Social Connect End-->
                    <!--Product code and Seller Start-->
                    @include('front.products.partials.review_feedback_stats')
                    <!--Product code and Seller End-->
                    <!--Product Specifiaction Start-->
                    <div class="product-spec">
                            <div class="leftcol">
                                <span class="medium">20 Available 50 Sold</span>
                                <div class="small-semibold pull-right"><span class="normal">Item Condition:</span> New</div>
                                <div class="filter">
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
                                    </div>
                                    <?php }?>
                                </div>
                            </div>

                            <div class="rightcol flexbox">
                                <div class="product-spec-btn">
                                    <div class="button"><div class="inner">Warranty<span> {{ (isset($product->warranty_applicable) && ($product->warranty_applicable == 'yes')) ? '1 Year Warranty' : 'No' }}</span></div></div>
                                    <div class="button"><div class="inner">Returns<span> {{ (isset($product->is_return_applicable) && ($product->is_return_applicable == 'yes')) ? '30 Days Returns' : 'No' }}</span></div></div>
                                    <div class="button"><div class="inner">Shipping<span>Free Shipping</span></div></div>
                                </div>
                                <div class="product-spec-logo">
                                    <img src="{{asset('assets/front/img/quality-logo.png')}}" align="Quality Logo" width="114" height="108">
                                </div>
                            </div>
</div>
@push('scripts')
   
@endpush
                    <!--Product Specifiaction End-->
                    <!--Product Price and Button Start-->            
                    @include('front.products.partials.price')
                    <!--Product Price and Button End-->               
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
                        
                        @include('front.products.partials.tabs.payment')
                        @include('front.products.partials.tabs.shipping_returns')
                        @include('front.products.partials.tabs.review')
                    </div>
                </div>
                <!--Product description Tab Close-->
                <!--Product review start-->
                @include('front.products.partials.review_detail')
                <!--Product review end-->
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