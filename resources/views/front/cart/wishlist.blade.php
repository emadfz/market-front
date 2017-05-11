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
                    <th class="col4">price($)</th>
                    <th class="col5">action</th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                foreach(Cart::instance('favorite')->content() as $row) :?>
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
                    <td class="col4" data-title="Price"><?php print(convert_currency($row->price, session()->get('currency_rate')));?></td>
                    <td class="col5" data-title="Action">
                    	<a href="javascript: addtocart('<?php echo $row->id;?>','1')" title="Add to cart">Add to cart</a>
                        <a href="javascript: addtowatchlist('<?php echo $row->id;?>','1','<?php echo $row->rowId;?>')" title="Add to cart">Add to watch list</a>
                    	<a href="javascript: void(0);" onclick="removefromwishlist('{{$row->rowId}}')" title="Remove from Wishlist" class="text-danger">Remove from Wishlist</a>
                    </td>
                  </tr>
                  <?php endforeach;?>
                </tbody>
              </table>
            </div>
          </div>
          <!--Leftside Start -->
          <!--Rightside Start -->
          </div>
          <!--Rightside End --> 
          </div> 
