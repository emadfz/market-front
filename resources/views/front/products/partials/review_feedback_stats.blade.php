<div class="code-seller clearfix">
    <div class="leftcol">
        <div class="seller-outer">
            <div class="medium">Product Code:<span>	{{$product->system_generated_product_id}}</span></div>
        </div>
        <div class="seller-outer">
            <div class="rating pull-left"><img src="{{asset('assets/front/img/star-medium.png')}}" alt="" width="83" height="14"></div>
            <div class="review-icon viewreview-dropdown">
                
                            <div class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Compare">5 Review</a>
								<div class="dropdown-menu" aria-labelledby="dLabel">
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
                                    <div class="viewreview-outer clearfix">
                                    <!-- default -->
                                        	<div class="circle-outer">
                                                <div class="c100 p12 small green">
                                                    <span>12%</span>
                                                    <div class="slice">
                                                        <div class="bar"></div>
                                                        <div class="fill"></div>
                                                    </div>
                                                </div>
                                                <p>Good value</p>
                                            </div>
                                            <div class="circle-outer">
                                            <div class="c100 p99 small dark">
                                            	<span>99%</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div>
                                            	<p>Long battery</p>
                                            </div>
                                            <div class="circle-outer">
                                            <div class="c100 p100 small orange">
                                            	<span>100%</span>
                                                <div class="slice">
                                                    <div class="bar"></div>
                                                    <div class="fill"></div>
                                                </div>
                                            </div>
                                            	 <p>Small from factor</p>
                                            </div>
                                    <!-- /default -->
                                    </div>
                                    <a href="https://google.com" class="btn btn-block showmore" title="See Reviews">See all 8 reviews</a>
                          		</div>
							</div>
                            <a href="#" title="Write a Review"><em></em>Write a Review</a>
                         </div>
                
            </div>
        <p>899 Viewed per day</p>
    </div>

    <div class="rightcol">
        <span class="seller-tag"></span>
        <div class="seller-block">
            <div class="seller-outer">
                <div class="medium pull-left">Seller:<span>{{$product->manufacturer}}</span></div>
                <div class="sellerlink pull-right">
                    <a href="#" title="Contact Seller">Contact Seller</a>                    
                    <a href="{{URL('/sellerstore/'.urlencode($product->manufacturer))}}" title="Visit Store">Visit Store</a>                    
                </div>
            </div>
            <div class="seller-outer">
                <span class="rating"><img src="{{asset('assets/front/img/star-medium.png')}}" alt="" width="83" height="14"></span>
                <span class="bluetext">97.5% Positive Feedback</span>
            </div>
        </div>
    </div>
</div>