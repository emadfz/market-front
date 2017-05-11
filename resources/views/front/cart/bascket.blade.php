
          <div class="manage-cart clearfix">
          	<div class="custom-checkbox">
	           <label><input type="checkbox" value="" id="checkAll"><span></span></label>
                </div>
            <a href="javascript: void(0);" title="Delete" class="delete-text" onclick="remove_cart();"><i class="delete"></i>Delete</a>
          </div>
          <div class="shopping-colmain">
          <!--Leftside Start -->
          <div class="shopping-leftcol">
           	<div class="table-responsive">
              <table class="table table-bordered cart-table mobile-table">
                <thead>
                  <tr>
                    <th class="col1">Product</th>
                    <th class="col2">Availability</th>
                    <th class="col3">quantity</th>
                    <th class="col4">price($)</th>
                    <th class="col5">action</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                foreach(Cart::content() as $row) :?>
                  <tr>
                    <td class="col1" data-title="Product">
                    	<div class="custom-checkbox">
	                        <label><input type="checkbox" class= "c_b" id="c_b" name="c_b" value="{{$row->rowId}}"><span></span></label>
                      	</div>
                        <div class="thumbbox"> <img src="http://php54.indianic.com/marketplace-front/public/images/products/main/<?php echo ($row->options->has('image') ? $row->options->image : asset('assets/front/img/xs-thumb1.jpg')); ?>" width="42" height="38" alt=""> 
                        </div>
                        <p><span><?php echo $row->name; ?></span>
                            <span>Seller: 
                                <?php echo ($row->options->has('manufacturer') ? $row->options->manufacturer : ''); ?>
                            </span>
                        </p>
                    </td>
                    <td class="col2" data-title="Available">Yes</td>
                    <td class="col3" data-title="Quantity">
                        <?php /*<a class="cart_quantity_up" href="javascript: incrementcart('<?php echo $row->rowId;?>','1');"> + </a>*/ ?>
                        <input type="text" class="form-control" value="<?php echo $row->qty; ?>" onblur="updateqtycart('<?php echo $row->rowId;?>',this.value)">
                        <a href="javascript: void(0);" title="Delete" class="tr-delete" onclick="removefromcart('<?php echo $row->rowId;?>')">Delete</a>
                        <?php /* ?><a class="cart_quantity_up" href="javascript: decrease('<?php echo $row->rowId;?>','1');"> - </a>                  <?php */ ?>  </td>
                    <td class="col4" data-title="Price"><?php print(convert_currency($row->price, session()->get('currency_rate')));?>  </td>
                    <td class="col5" data-title="Action"><a href="javascript: addtofavorite('<?php echo $row->id;?>','1')" title="Add to Wishlist">Add to Wishlist</a></td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
          <!--Leftside Start -->
          <!--Rightside Start -->
          <div class="shopping-rightcol">
          	<div class="cart-topbar">
            	<span class="cart-itmes">Item(s)<span><?php echo count(Cart::content()); ?></span></span>
                <span class="cart-title">Total</span>
            	<span class="cart-total"> {{ convert_currency(Cart::subtotal(), session()->get('currency_rate'))}} 
                </span>
            </div>
            <div class="innercart-outer">
                <div class="cart-btn">
                <?php if(Auth::id()) {?>
                <a href="javascript: void(0);" onclick="cart_to_order();" class="btn btn-sm btn-primary disabled basket_class" title="Proceed to Checkout">Proceed to Checkout</a>
                <?php } else { ?>
                <a href="javascript: void(0);" onclick="$('.signinModal').click();" class="btn btn-sm btn-primary disabled basket_class" title="Proceed to Checkout">Proceed to Checkout</a>
                <?php } ?>
                <a href="{{route('homepage')}}" class="link" title="Continue Shopping">Continue Shopping</a>
                </div>
            </div>
          </div>
          <!--Rightside End --> 
          </div> 