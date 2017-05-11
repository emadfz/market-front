<div class="fullcolumn prodetail-realestate automobile-detail">
        <div class="row"> 
          <!--Leftsidebar start-->
          <div class="col-md-8 product-leftcol clearfix">
            <div class="productdata-gallery">
              <div class="ribbon day"><span>Used</span></div>
              <div class="zoom"><span></span>Click to Zoom<a href="#" title="Favorite" class="like"></a></div>
              
              <ul class="bxslider-realauto">
                @foreach ($product->Files as $file)
                <li>
                    <a class="fancybox" href="{{ env("APP_URL").'/images/products/main/'.$file->path }}" rel="gallery">
                        <img src="{{ env("APP_URL").'/images/products/small/'.$file->path }}" alt="" width="820" height="450">
                    </a>
                </li>
                @endforeach
              </ul>
              <div id="bx-pager"> 
                  <?php $si=0;?>
              	 @foreach ($product->Files as $file)
              	<a data-slide-index="{{$si}}" href="" class="active"><img src="{{ env("APP_URL").'/images/products/small/'.$file->path }}" alt="" width="101" height="74"></a> 
                <?php $si++;?>
                @endforeach
                <!--<a data-slide-index="7" href=""><img src="bootstrap/img/small-8.jpg" alt="" width="101" height="74"><span class="video-icon"></span></a> --> 
              </div>
            </div>
          </div>
          
          <!--leftsidebar close--> 
          <!--Rightsidebar Start-->
          <div class="col-md-4 padd-leftnone product-rightcol">
            <div class="price-row clearfix"> 
            	<span class="baseprice">$<span id='final_price'><?php print(convert_currency( $product->base_price} , session()->get('currency_rate')));?></span></span> 
                <span class="sellerlink"> 
                	<a href="#paymentcalculator" title="Estimate a  Payment" data-toggle="modal">Estimate a Payment</a> 
                    <a href="#" title="View Rates">View Rates</a> 
                </span> 
            </div>
              @include('front.products.partials.title')
                  <?php
                      $address = $product->productOriginAddress->address_1.", ".$product->productOriginAddress->address_2.", ".$product->productOriginAddress->city->city_name." ".$product->productOriginAddress->state->state_name." ".$product->productOriginAddress->country->country;
                      ?>
                  <span>
                       {{$address}}<i class="location-icon"></i></span>
            <!--Social Connect start-->
            @include('front.products.partials.social_share')
            <!--Social Connect End-->
            <div class="product-type"> 
            	<span class="medium">Condition:<span>Great</span></span> 
                <div class="selectbox width125 pull-right"> 
                            
                    <?php
            $keys=array_keys($attributes);
            for($i=0;$i<count($keys);$i++){ ?>
            <div class=" clearfix">
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
            <!--Product code and Seller Start-->
            @include('front.products.partials.review_feedback_stats')
            <!--Product code and Seller End--> 
            <!--Product Price and Button Start-->
            <div class="price-btn">
              <ul class="print-mail clearfix">
                <li class="print"><a href="#" title="Print"><span></span>print</a></li>
                <li class="email"><a href="#" title="Email"><span></span>email</a></li>
                <li class="flag"><a href="#" title="Report"><span></span>report</a></li>
              </ul>
              <hr>
              <div class="button-col">
                <div class="clearfix"> <a href="#setpreview-modal" data-toggle="modal" class="btn btn-primary" title="Set a preview">Set a preview</a> 
                  <a href="#" class="btn btn-primary" title="Add to cart" onclick="addtocartSku({{$product->productSkusVariantAttribute[0]->id}},1);">Add to cart</a>
                  
                  <a href="#" title="Add to Compare" class="bluetext show nomargin">Add to Compare</a> </div>
              </div>
            </div>
            <!--Product Price and Button End--> 
            
          </div>
          <!--Rightsidebar Close--> 
        </div>
        
        <hr>
        <div class="row "> 
          <!--RealEstate description Tab start-->
          <div class="col-lg-12 product-desc"> 
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#propertydata" role="tab" data-toggle="tab">Property Details</a></li>
              <li role="presentation"><a href="#historyreport" role="tab" data-toggle="tab">History Report</a></li>
              <li role="presentation"><a href="#nearby" role="tab" data-toggle="tab">Schools &amp; Neighborhood</a></li>
              <li role="presentation"><a href="#paymentpolicy" role="tab" data-toggle="tab">Payment Policy</a></li>
              <li role="presentation"><a href="#PropertyReviews" role="tab" data-toggle="tab">Property Reviews</a></li>
              <li role="presentation"><a href="#Location" role="tab" data-toggle="tab">Location</a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="propertydata">
                <h3>Propery Details</h3>
                <div class="body-semibold">Overview</div>
                <p>The median price for this area is 109900. The 2 assigned schools for this property are located in Sangamon Valley Community Unit School District 9. There are currently 44 similar properties for sale within 10-mile radius, ranging from $1 - $389,900.</p>
                <div class="row property-viewcol">
                  <div class="col-sm-6">
                    <div class="body-semibold">Overview</div>
                    <ul class="ul-bluebullet">
                      <li>Price/Sq Ft: $65</li>
                    </ul>
                  </div>
                  <div class="col-sm-6">
                    <div class="body-semibold">Schools</div>
                    <ul class="ul-roundbullet">
                      <li class="darkgary"><span>9</span>Illiopolis Elementary School</li>
                      <li><span>5</span>Sangamon Valley Middle School</li>
                    </ul>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="body-semibold">Public Record</div>
                    <ul class="ul-bluebullet recordtype">
                      <li class="col-sm-4">House size:<small>sq ft</small></li>
                      <li class="col-sm-4">Lot size:<small>sq ft</small></li>
                      <li class="col-sm-4">Heating:<small>Unknown</small></li>
                      <li class="col-sm-4">Cooling:<small>Unknown</small></li>
                      <li class="col-sm-4">Date updated:<small>07/14/2015</small></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="historyreport">
                <h3>Property History</h3>
                <div class="body-semibold">Nearby Neighborhoods</div>
                <p>No price history available for this property.</p>
                <hr class="mrg-top10">
                <div class="body-semibold">Property Tax</div>
                <table class="table table-bordered property-table mobile-table">
                  <thead>
                    <tr>
                      <th class="col1">Year</th>
                      <th class="col2">Taxes</th>
                      <th class="col3">Land</th>
                      <th class="col4">Additions</th>
                      <th class="col5">Total Assessment</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="col1" data-title="Year">2014</td>
                      <td class="col2" data-title="Taxes">$137</td>
                      <td class="col3" data-title="Land">$1,821<span>+</span></td>
                      <td class="col4" data-title="Additions">N/A<span>=</span></td>
                      <td class="col5" data-title="Total Assessment">$1,821</td>
                    </tr>
                    <tr>
                      <td class="col1" data-title="Year">2013</td>
                      <td class="col2" data-title="Taxes">$133</td>
                      <td class="col3" data-title="Land">$1,721<span>+</span></td>
                      <td class="col4" data-title="Additions">N/A<span>=</span></td>
                      <td class="col5" data-title="Total Assessment">$1,721</td>
                    </tr>
                    <tr>
                      <td class="col1" data-title="Year">2012</td>
                      <td class="col2" data-title="Taxes">$129</td>
                      <td class="col3" data-title="Land">$1,761<span>+</span></td>
                      <td class="col4" data-title="Additions">N/A<span>=</span></td>
                      <td class="col5" data-title="Total Assessment">$1,761</td>
                    </tr>
                  </tbody>
                </table>
                <div class="borderbox nomargin">
                  <div class="body-semibold">About History &amp; Taxes Data</div>
                  <p>The price and tax history data displayed is obtained from public records and/or MLS feeds from the local jurisdiction. Contact your REALTOR® directly in order to obtain the most up-to-date information available.</p>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="nearby">
                <h3>Schools &amp; Neighborhood</h3>
                <?php
                $json = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=48.859294,2.347589&radius=500&types=school&sensor=false&key=AIzaSyDN1QX-gWUR-mIYo_D21PNFLHHpNQkIkGU");
                $json = json_decode($json);
                ?>
                <div class="body-semibold">Schools</div>
                <div class="titlewith-table">
                  <div class="body-semibold">Assigned Schools</div>
                  <table class="table table-bordered nearby-table mobile-table">
                    <thead>
                      <tr>
                        <th class="col2">School</th>
                        <th class="col4">Distance</th>
                      </tr>
                    </thead>
                    <tbody>
                        {{ !$resultCount= (count($json->{'results'})<5)?count($json->{'results'}):5 }}
                        @for($i=0;$i<$resultCount;$i++)
                      <tr>
                        <td class="col2" data-title="School"><span><?php echo $json->{'results'}[$i]->{'name'} ?></span></td>
                        <td class="col4" data-title="Distance">{{latlog_distance(48.859294,2.347589, $json->{'results'}[$i]->{'geometry'}->{'location'}->{'lat'}, $json->{'results'}[$i]->{'geometry'}->{'location'}->{'lat'}, "M")}}</td>
                      </tr>
                        @endfor
                    </tbody>
                  </table>
                </div>
                <div class="titlewith-table">
                  <div class="body-semibold">Assigned Hospital</div>
                  <?php
                $json = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=48.859294,2.347589&radius=500&types=hospital&sensor=false&key=AIzaSyDN1QX-gWUR-mIYo_D21PNFLHHpNQkIkGU");
                $json = json_decode($json);
                ?>
                  <table class="table table-bordered nearby-table mobile-table">
                    <thead>
                      <tr>
                        <th class="col2">Hospital</th>
                        <th class="col4">Distance</th>
                      </tr>
                    </thead>
                    <tbody>
                        {{ !$resultCount= (count($json->{'results'})<5)?count($json->{'results'}):5 }}
                        @for($i=0;$i<$resultCount;$i++)
                      <tr>
                        <td class="col2" data-title="School"><span><?php echo $json->{'results'}[$i]->{'name'} ?></span></td>
                        <td class="col4" data-title="Distance">{{latlog_distance(48.859294,2.347589, $json->{'results'}[$i]->{'geometry'}->{'location'}->{'lat'}, $json->{'results'}[$i]->{'geometry'}->{'location'}->{'lat'}, "M")}}</td>
                      </tr>
                        @endfor
                    </tbody>
                  </table>
                </div>
                <div class="titlewith-table">
                  <div class="body-semibold">Assigned Park</div>
                  <?php
                $json = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=48.859294,2.347589&radius=500&types=park&sensor=false&key=AIzaSyDN1QX-gWUR-mIYo_D21PNFLHHpNQkIkGU");
                $json = json_decode($json);
                ?>
                  <table class="table table-bordered nearby-table mobile-table">
                    <thead>
                      <tr>
                        <th class="col2">Park</th>
                        <th class="col4">Distance</th>
                      </tr>
                    </thead>
                    <tbody>
                        {{ !$resultCount= (count($json->{'results'})<5)?count($json->{'results'}):5 }}
                        @for($i=0;$i<$resultCount;$i++)
                      <tr>
                        <td class="col2" data-title="School"><span><?php echo $json->{'results'}[$i]->{'name'} ?></span></td>
                        <td class="col4" data-title="Distance">{{latlog_distance(48.859294,2.347589, $json->{'results'}[$i]->{'geometry'}->{'location'}->{'lat'}, $json->{'results'}[$i]->{'geometry'}->{'location'}->{'lat'}, "M")}}</td>
                      </tr>
                        @endfor
                    </tbody>
                  </table>
                </div>
                
              </div>
              <div role="tabpanel" class="tab-pane" id="paymentpolicy">4</div>
              <div role="tabpanel" class="tab-pane" id="PropertyReviews"> 
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
                    	<textarea name="ckeditor" cols="30" rows="5" class="ckeditor" placeholder="Comment"></textarea>
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
              <div role="tabpanel" class="tab-pane" id="Location"> 
                  <iframe alt="nearby-map" width="1120" height="361" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.it/maps?q=48.859294,2.347589&output=embed"></iframe>
              </div>
            </div>
          </div>
          <!--Product description Tab Close--> 
          
        </div>
      </div>
@push('scripts')
<script src="{{ asset('assets/front/js/jquery.fancybox.js') }}"></script> 
<!--<script src="bootstrap/js/jquery.fancybox-media.js"></script>  -->
<!-- Custom javascript code goes in general.js file... --> 
<script type="text/javascript"> //Product Detail Gallery Start RealEstate,Automobile
       
         $(document).ready(function(){
            $('.bxslider-realauto').bxSlider({
                pagerCustom: '#bx-pager',
				//mode: 'fade'
			});
			$(".fancybox").fancybox({
                        autoPlay:true,
			   afterShow: function () {
            		$(".fancybox-outer").on("mouseover", function () {
                            $.fancybox.play(false); // stops slideshow
            		});
        	   },
			   //nextEffect : 'fade',
			   //prevEffect : 'fade',
			   closeClick  : false, // prevents closing when clicking INSIDE fancybox
   				 helpers     : { 
        			overlay : {closeClick: false} // prevents closing when clicking OUTSIDE fancybox
    			}
			});
		});  
</script> 
@endpush