          <div class="shopping-colmain update-shoppingcart">
          <!--Leftside Start -->
          <div class="shopping-leftcol">
           	
              <table class="table table-bordered updatecart-table tablet-table">
                <thead>
                  <tr>
                    <th class="col1">
                    	<div class="custom-checkbox">
	           <label><input type="checkbox" value="" id="checkAll"><span></span></label>
            			</div>
            			<a href="javascript: void(0);" title="Delete"  onclick="remove_bascket();" class="delete-text mrg-right10"><i class="delete"></i></a>Product
                    </th>
                    <th class="col2">QTY.</th>
                    <th class="col3">Address &amp; Shipping Detail</th>
                    <th class="col4">price($)</th>
                    <th class="col5">Promo Code</th>
                    <th class="col6">Total</th>
                  </tr>
                </thead>
                <tbody>
                        {{!$x=1}}
                      @foreach(Cart::instance('shopping')->content() as $row)
                  <tr>
                    <td class="col1" data-title="Product">
                    	<div class="custom-checkbox">
	                        <label><input type="checkbox" class= "c_b" id="c_b" name="c_b" value="{{$row->rowId}}"><span></span></label>
                      	</div>
                        <div class="thumbbox"> <img src="http://php54.indianic.com/marketplace-front/public/images/products/main/<?php echo ($row->options->has('image') ? $row->options->image : asset('assets/front/img/xs-thumb1.jpg')); ?>" width="42" height="38" alt=""> </div>
                        <p><span>{{$row->name}}</span><span>Seller: {{$row->options->has('manufacturer') }}{{ $row->options->manufacturer}}</span></p>
                    </td>
                    <td class="col2" data-title="Quantity">
                        <input type="text" class="form-control" value="<?php echo $row->qty; ?>" onblur="updateqtycart('{{ $row->rowId}}',this.value)">
                    </td>
                    <td class="col3" data-title="Address & Shipping">
                        <div class="shipadd-outer">
                    	<div class="selectbox width150">
                        	<select class="selectpicker">
                            	<option>Ganesh Hulle</option>
                                <option>vinaykant patel</option>
                                <option>vinaykant patel vinaykant patel</option>
                            </select>
                        </div>
                        <div class="selectbox width50">
                        	<select class="selectpicker">
                            	<option>1</option>
                                <option>205</option>
                            </select>
                        </div>
                        <span>
                            Free Shipping: 3-5 business days 
                            <a class="ajaxModal" data-target="#editaddress1" href="{{URL('/shippingServices/99205/99306')}}" data-toggle="modal" data-placement="middle" title="Edit">Edit</a>
                        </span>
                        </div>
                    </td>
                    <td class="col4" data-title="Price"> <?php print(convert_currency($row->price, session()->get('currency_rate')));?></td>
                    <td class="col5" data-title="Promo Code">
                    	<input type="text" class="form-control promo_code" data-apply-id="{{'apply_'.$x}}" id="{{$x}}" placeholder="Promo Code">
                        <span><a href="#" id="{{'apply_'.$x}}" onclick="check_promo(document.getElementById('{{$x}}').value, {{\Auth::id()}})" title="Apply">Apply</a></span>
                    </td>
                    <td class="col6" id="{{'td'.$x}}" data-title="Total"> <?php print(convert_currency($row->price*$row->qty, session()->get('currency_rate')));?></td>
                  </tr>
                  {{!$x++}}
                  
                 @endforeach
                </tbody>
              </table>
            
          </div>
          <!--Leftside Start -->
          <!--Rightside Start -->
          <div class="shopping-rightcol">
          	<div class="cart-topbar">
            	<span class="cart-itmes">Item(s)<span><?php echo count(Cart::content()); ?></span></span>
                <span class="cart-title">Total</span>
             <span class="cart-total" ><span id="cart-total"> {{ convert_currency(Cart::subtotal(), session()->get('currency_rate'))}} </span></span>
            </div>
            <div class="innercart-outer">
				<div class="cart-labelfiled clearfix">
            		<div class="cart-label">Use Promo code for discount (Site Promotion Code )</div>
                	<div class="cart-field"><input type="text" class="form-control" placeholder="Promo Code"></div>
				</div>
                <div class="cart-labelfiled clearfix mrg-top20" id="cart_total">
            		<div class="cart-label">Appled Discount:</div>
                	<div class="cart-field distotal">$00.00</div>
				</div>
            </div>

             <div class="innercart-outer">
            	<div class="cart-btn">
            	<a href="{{route('payment')}}" class="btn btn-sm btn-primary" title="Proceed to Payment">Proceed to Payment</a>
                <a href="{{route('homepage')}}" class="link" title="Continue Shopping">Continue Shopping</a>
                </div><?php /*
                 <label for="vendor">Donation for</label>
                     <select  class="form-control" id="vendor">
                         @foreach($donations as $donation)
                         <option value="{{$donation->vendor_name}}">{{$donation->vendor_name}}</option>
                         @endforeach
                     </select>
                 <label for="donation_amount">Amount</label>

                 <input type="number" class="form-control" style="  " min="0"    name="donation_amount" id="donation_amount">
                 <br>
                 <button id="add_cart_value" class="btn btn-warning" >Add</button>*/ ?>
            </div>
          </div>
          <!--Rightside End --> 
          </div>

