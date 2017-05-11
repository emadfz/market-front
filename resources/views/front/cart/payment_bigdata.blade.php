

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if(isset($savedCards) && count($savedCards)>0)
<div class="container">
    <div class="row">
        <!-- You can make it whatever width you want. I'm making it full width
             on <= small devices and 4/12 page width on >= medium devices -->
        <div class="col-xs-12 col-md-12">
        
        
            <!-- CREDIT CARD FORM STARTS HERE -->
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading">
                    <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Saved Cards</h3>
                        <div class="display-td" >                            
                            
                        </div>
                    </div>                    
                </div>
                <div class="panel-body">
                {!! Form::open(array('url' => 'makepaymentwithtoken')) !!}
    
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label for="cardExpiry">Amount</label>                                    
                                    {{ Form::text('amount', null, ['class' => 'form-control required','required']) }} 
                                    <label for="cardExpiry">Reference Number(ORDER ID)</label>
                                    {{ Form::text('ref_no', 'ORD_'.str_random(10), ['class' => 'form-control required','required']) }} 
                                    <label for="cardExpiry">Select Saved payment source</label>                                    
                                    @foreach(@$savedCards as $row)
                                        {{ Form::radio('card_type', $row['card_type'], false, ['class' => 'field required','required']) }}  
                                        <?php 
                                                echo $row['card_type'].'&nbsp;&nbsp;'; 
                                                echo str_repeat("*",strlen($row['token'])-4);echo substr($row['token'], strlen($row['token'])-4); 
                                        ?>   
                                        <a href="{{URL('/removecard/'.$row['card_type'])}}" onclick="if(confirm('Are you sure to delete this card?')==false){return false;}" style='padding:0 10px 0 10px;background-color:lightgrey;'>Remove Card</a><br/>
                                    @endforeach
                                </div>
                            </div>                            
                        </div>
                    

                        <div class="row">
                            <div class="col-xs-12">
                                <input type="submit" class="subscribe btn btn-success btn-lg btn-block" value="Pay" />
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-xs-12">
                                <p class="payment-errors"></p>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>            
            <!-- CREDIT CARD FORM ENDS HERE -->
            
            
        </div>            
        
        
        
    </div>
</div>
@endif


<div class="container">
    <div class="row">
        <!-- You can make it whatever width you want. I'm making it full width
             on <= small devices and 4/12 page width on >= medium devices -->
        <div class="col-xs-12 col-md-12">
        
        
            <!-- CREDIT CARD FORM STARTS HERE -->
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading">
                    <div class="row display-tr " >
                        <h3 class="panel-title display-td " >Payment Details</h3>
                        <div class="display-td" >                            
                            <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                        </div>
                    </div>                    
                </div>
                <div class="panel-body">
                {!! Form::open(array('url' => 'makepayment')) !!}
    
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label for="cardExpiry">CARD TYPE</label>
                                    {{ Form::select('card_type', array(''=>'Select','Visa'=>'Visa','MasterCard'=>'MasterCard','Discover'=>'Discover','American Express'=>'American Express','JCB'=>'JCB'), null, ['class' => 'form-control required','required']) }}
                                </div>
                            </div>                            
                        </div>
                    
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cardNumber">CARD NUMBER</label>
                                    <div class="input-group">
                                        {{ Form::text('card_number', null, ['class' => 'form-control required number','placeholder'=>'Card Number','required']) }}
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>                            
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="cardNumber">CVV</label>
                                    <div>
                                        {{ Form::text('cvv', null, ['class' => 'form-control required number','placeholder'=>'cvv','required']) }}                                        
                                    </div>
                                </div>                            
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cardNumber">CARD NAME</label>
                                    <div class="input-group">
                                        {{ Form::text('card_name', null, ['class' => 'form-control required','placeholder'=>'Card Name','required']) }}
                                        <span class="input-group-addon"><i class="fa fa-credit-card"></i></span>
                                    </div>
                                </div>                            
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <div class="form-group">
                                    <label for="cardExpiry"><span class="hidden-xs">EXP</span><span class="visible-xs-inline">EXP</span> MONTH</label>
                                    {{ Form::selectRange('expiration_month', 01, 12, null, ['class' => 'form-control required','placeholder'=>'MM','required']) }}
                                </div>
                            </div>
                            <div class="col-xs-6 col-md-6 pull-right">
                                <div class="form-group">
                                    <label for="cardExpiry"><span class="hidden-xs">EXP</span><span class="visible-xs-inline">EXP</span> YEAR</label>
                                    {{ Form::selectRange('expiration_year', date('y'), date('y')+24, null, ['class' => 'form-control required','placeholder'=>'YY','required']) }}
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cardNumber">AMOUNT</label>
                                    <div>
                                        {{ Form::text('amount', Cart::instance('shopping')->subtotal(), ['class' => 'form-control required','placeholder'=>'Amount','required']) }}
                                    </div>
                                </div>                            
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="cardNumber">Reference Number(ORDER ID)</label>
                                    <div>
                                        {{ Form::text('ref_no', 'ORD_'.str_random(10), ['class' => 'form-control required','placeholder'=>'Reference Number','required']) }}
                                    </div>
                                </div>                            
                            </div>
                        </div>                        



                         <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">
                                    <label for="cardExpiry">Billing Address</label>                                                                   
                                    {{ Form::text('address', null, ['class' => 'form-control required','placeholder'=>'Address Line 1']) }}
                                </div>
                            </div>
                        </div>
                        
                     <!--    <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">                                    
                                    <label for="addr_1">Address Line 2</label>                               
                                    {{ Form::text('addr_2', null, ['class' => 'form-control required','placeholder'=>'Address Line 2']) }}
                                </div>
                            </div>
                        </div> -->


<!--                         <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">                                    
                                    <label for="city">City</label>                               
                                    {{ Form::text('city', null, ['class' => 'form-control required','placeholder'=>'City']) }}
                                </div>
                            </div>
                        </div> -->


<!--                         <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">                                    
                                    <label for="city">State</label>                               
                                    {{ Form::text('state', null, ['class' => 'form-control required','placeholder'=>'state']) }}
                                </div>
                            </div>
                        </div>
 -->

                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <div class="form-group">                                    
                                    <label for="city">Zip Code</label>                               
                                    {{ Form::text('zip', null, ['class' => 'form-control required','placeholder'=>'Zip Code']) }}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-12">
                                <input type="submit" class="subscribe btn btn-success btn-lg btn-block" value="Pay" />
                            </div>
                        </div>
                        <div class="row" style="display:none;">
                            <div class="col-xs-12">
                                <p class="payment-errors"></p>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>            
            <!-- CREDIT CARD FORM ENDS HERE -->
            
            
        </div>            
        
        
        
    </div>
</div>
