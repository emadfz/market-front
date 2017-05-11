 {!! Form::open(array('url' => 'makepayment')) !!}
<div class="payment-page clearfix">
          <div class="head clearfix">
          	<span class="cart-shipview">Shipping:<span>$200</span></span>
          	<div class="head-right">
          		<span class="cart-title">Total
                	<span class="cart-itmes">Item(s)<span>{{count(Cart::instance('shopping')->content())}}</span></span>
                </span>
            	<span class="cart-total">{{convert_currency(Cart::instance('shopping')->subtotal() , session()->get('currency_rate')) }}</span>
            </div>
          </div>
          <div class="payment-type">
          	<div class="row">
            	<div class="col-md-12 mrg-bott20">
                	<h4>Choose a payment method</h4>
                    <div class="custom-radio">
                      <label for="ccdbcard">
                        <input id="ccdbcard" type="radio" value="ccdbcard" name="payment_method" checked>
                        <span></span>Credit Card/Debit Card</label>
                      <label for="ebttransfer">
                        <input id="ebttransfer" type="radio" value="ebttransfer" name="payment_method">
                        <span></span>EBT/Wire-transfer</label>
                      <label for="PayPal">
                        <input id="PayPal" type="radio" value="PayPal" name="payment_method">
                        <span></span>PayPal</label>
                    </div>
                </div>
                <div class="col-md-12 payment-source">
                	<h4>Select saved payment source</h4>
                    <div class="custom-radio">
                        <label for="source1" class="mastercard">
                            <input type="radio" name="payment_source" checked="" id="source1">
                            <span></span>
                            <span class="cardname">
                            	<span></span> 
                                <small>Master Card</small>
                            </span>
                            <span class="card-number">************3145</span>
                            <a href="#" title="Remove Card" class="link">Remove Card</a>
                        </label>
                        <label for="source2" class="visacard">
                            <input type="radio" name="payment_source" id="source2">
                            <span></span>
                            <span class="cardname">
                            	<span></span> 
                                <small>Visa Card</small>
                            </span>
                            <span class="card-number">************3145</span>
                            <a href="#" title="Remove Card" class="link">Remove Card</a>
                        </label>
                        <label for="source3" class="americancard">
                            <input type="radio" name="payment_source" id="source3">
                            <span></span>
                            <span class="cardname">
                            	<span></span> 
                                <small>American Express</small>
                            </span>
                            <span class="card-number">************3145</span>
                            <a href="#" title="Remove Card" class="link">Remove Card</a>
                        </label>
                        <label for="source4" class="discovercard">
                            <input type="radio" name="payment_source" id="source4">
                            <span></span>
                            <span class="cardname">
                            	<span></span> 
                                <small>Discover</small>
                            </span>
                            <span class="card-number">************3145</span>
                            <a href="#" title="Remove Card" class="link">Remove Card</a>
                        </label>
                    </div>
                </div>
                <div class="payment-info">
                	<h4>Select saved payment source<!--<span>Don't have Credit card? <a href="#" class="link">Apply online</a></span> --></h4>
                	<div class="form-horizontal col-md-6 col-sm-6">
                    	<div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Card Type</label>
                            <div class="col-md-7 col-sm-7">
                                {{ Form::select('card_type', array(''=>'Select','Visa'=>'Visa','MasterCard'=>'MasterCard','Discover'=>'Discover','American Express'=>'American Express','JCB'=>'JCB'), null, ['class' => 'form-control required selectpicker','required']) }}
                    		</div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Card Number</label>
                            <div class="col-md-7 col-sm-7">
                                        {{ Form::text('card_number', null, ['class' => 'form-control required number','placeholder'=>'Card Number','required']) }}
                    		</div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Expiration Date</label>
                            <div class="col-md-7 col-sm-7">
                      			
                                {{ Form::selectRange('expiration_month', 01, 12, null, ['class' => 'selectpicker ','placeholder'=>'MM','required','data-width'=>'100px']) }}
                                {{ Form::selectRange('expiration_year', date('y'), date('y')+24, null, ['class' => 'selectpicker','placeholder'=>'YY','required','data-width'=>'100px'])}}
                    		</div>
                        </div>
                    </div>
                	<div class="form-horizontal col-md-6 col-sm-6">
                    	<div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Card Holder's Name</label>
                            <div class="col-md-7 col-sm-7">
                                        {{ Form::text('card_name', null, ['class' => 'form-control required','placeholder'=>'Card Name','required']) }}
                    		</div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">CVV</label>
                            <div class="col-md-7 col-sm-7">
                                        {{ Form::text('cvv', null, ['class' => 'form-control width100 pull-left mrg-right10 required number','placeholder'=>'cvv','required']) }}                                        
                                        {{ Form::hidden('ref_no', 'ORD_'.str_random(10), ['class' => 'form-control required','placeholder'=>'Reference Number','required']) }}
                                <a href="#" class="link">What's this ?</a>
                    		</div>
                        </div>
                        <!--<div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Accept Credit/Debit Card</label>
                            <div class="col-md-7 col-sm-7">
                      			<ul class="payment-option">
                    				<li><a href="#" class="visa">Visa</a></li>
                                    <li><a href="#" class="mastercard">Master Card</a></li>
                                    <li><a href="#" class="discover">Discover</a></li>
                                    <li><a href="#" class="ae">American</a></li>
                                </ul>
                    		</div>
                        </div> -->
                    </div>
                </div>
                <div class="payment-info">
                	<h4>Billing Address</h4>
                	<div class="form-horizontal col-md-6 col-sm-6">
                    	<div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Address 1</label>
                            <div class="col-md-7 col-sm-7">
                                         {{ Form::text('address1', null, ['class' => 'form-control required','placeholder'=>'Address Line 1']) }}
                    		</div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Address 2</label>
                            <div class="col-md-7 col-sm-7">
                                        {{ Form::text('address2', null, ['class' => 'form-control required','placeholder'=>'Address Line 2']) }}
                    		</div>
                        </div>
                         <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Zip Code</label>
                            <div class="col-md-7 col-sm-7">
                                    {{ Form::text('zip', null, ['class' => 'form-control required','placeholder'=>'Zip Code']) }}
                    		</div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Country</label>
                            <div class="col-md-7 col-sm-7">
                                {{ Form::select('country', array(''=>'Select','India'=>'India','USA'=>'USA'), null, ['class' => 'form-control required selectpicker','required']) }}
                            </div>
                        </div>
                    </div>
                	<div class="form-horizontal col-md-6 col-sm-6">
                    	<div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">Phone Number</label>
                            <div class="col-md-7 col-sm-7">
                      	    {{ Form::text('phone_number', null, ['class' => 'form-control required number','placeholder'=>'Phone Number']) }}
                    		</div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">State</label>
                            <div class="col-md-7 col-sm-7">
                      		{{ Form::select('state', array(''=>'Select','gujarat'=>'gujarat'), null, ['class' => 'form-control required selectpicker','required']) }}
                            </div>
                        </div>
                        <div class="form-group">
                        	<label class="control-label col-md-4 col-sm-5">City</label>
                            <div class="col-md-7 col-sm-7">
                      		    {{ Form::text('city', null, ['class' => 'form-control required','placeholder'=>'City']) }}
                    		</div>
                        </div>
                        
                        
                    </div>
                </div>
              
            </div>
              <hr class="mrg-topnone">
              <div class="clearfix text-right">
            	<a href="#" class="cancel-link btn-sm" title="Back">back</a>
                <a href="#" class="cancel-link btn-sm" title="Cancel">Cancel</a>
                <input type="button" title="Save Card" class="btn btn-primary btn-sm" value="Save Card">
            	<input type="submit" title="Pay Now" class="btn btn-primary btn-sm" value="Pay Now">
            </div>
          </div>
      </div>
 {!! Form::close() !!}