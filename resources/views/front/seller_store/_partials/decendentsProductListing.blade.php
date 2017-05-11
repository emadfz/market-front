<div class="rightcol-outer mrg-bottom">
    <div class="clearfix filter-grid">
        <div class="btn-group filter-checkbox" data-toggle="buttons">
            <label class="btn btn-gray active"><input type="checkbox" checked>All Listing</label>
            <label class="btn btn-gray"><input type="checkbox">Buy It Now</label>
            <label class="btn btn-gray"><input type="checkbox">Auction</label>
            <label class="btn btn-gray"><input type="checkbox">Buy It Now</label>
            <label class="btn btn-gray"><input type="checkbox">Auction</label>
            <label class="btn btn-gray"><input type="checkbox">Buy It Now</label>
        </div>
        <div class="gridlist-nav">

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active grid-icon"><a href="#gridview" role="tab" data-toggle="tab" title="Grid">Grid</a></li>
                <li role="presentation" class="list-icon"><a href="#listview" role="tab" data-toggle="tab" title="List">List</a></li>
            </ul>

            <!-- Tab panes -->


        </div>
    </div>    
    <div class="filterbar mrg-top10 clearfix">
        <div class="col-md-6 padd-leftnone">
            <span class="small-semibold">Sort By</span>
            <div class="selectbox"> 
                <select class="selectpicker">
                    <option selected="" value="">Best match</option>
                    <option value="">Top Sell</option>
                    <option value="">price</option>
                </select>
            </div>
        </div>
        <div class="col-md-6 text-right padd-rightnone">
            <ul class="pagination"> 
                <li class="disabled">
                    <a href="javascript:void(0)" aria-label="Previous" id="firstpage">
                        <span class="pagi-parent previous"></span>
                    </a>
                </li> 
                <li class="active">
                    <a href="javascript:void(0)" id="prev">
                        <span class="pagi-child previous"></span>
                        Prev
                    </a>
                </li> 
                <li><a href="#">{{$pageData}} of <span id="products_count">{{$products_count}}</span></a></li> 
                <li>
                    <a href="javascript:void(0)" id="next">
                        Next
                        <span class="pagi-child next"></span>
                    </a>
                </li> 
                <li>
                    <a href="javascript:void(0)" aria-label="Next" id="lastpage">
                        <span class="pagi-parent next" ></span>
                    </a>
                </li> 
            </ul>
        </div>
    </div>
</div>
<div class="rightcol-outer">
    <div class="tab-content content-white clearfix">
        <div role="tabpanel" class="tab-pane active clearfix" id="gridview">                                                        
            @foreach($products as $product)                                
            <div class="productbox col-md-4 col-sm-4 col-xs-6">
                <div class="borderhover">
                <ul class="bxslider-nopager">                                        
                    @if(isset($product->Files) && $product->Files->count()>0)
                        @foreach($product->Files as $file)
                        <li>
                            <a href="{{URL('product/'.$product->product_slug)}}">
                                <?php
                                    $condition1 = isset($file) && !empty($file);
                                ?>
                                @if($condition1)
                                    <img src="{{env('APP_URL')}}/images/products/main/{{@$file->path}}" alt="Compare" width="218" height="174">
                                @else
                                    <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="218" height="174">
                                @endif    
                            </a>
                        </li>
                        @endforeach                                                                                
                    @else                                                                                                                        
                    <li>
                        <a href="#">                                                                                                    
                            <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="218" height="174">
                        </a>
                    </li>
                    @endif
                </ul>
                </div>
                <div class="productbox-content">
                    <div class="item-detail">                       
                        <a class="title" href="{{URL('product/'.$product->product_slug)}}">
                            {{str_limit($product->name,40)}}
                        </a>                        
                        <div class="price">
                            <del>
                                <em>
                                      <?php print(convert_currency( $product->base_price, session()->get('currency_rate')));?> 
                                </em>
                            </del>
                            <span>
                                @foreach($product->productSkusVariantAttribute as $productSkusVariantAttribute)
                                    @if($productSkusVariantAttribute->is_default=="Yes")
                                       
                                      <?php print(convert_currency( $productSkusVariantAttribute->final_price, session()->get('currency_rate')));?> 

                                    @endif
                                @endforeach                                
                            </span>
                        </div>
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" data-productid="{{encrypt($product->id)}}" class="compare-icon">Compare</a>
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
            </div>
            @endforeach                                
        </div>
        <div role="tabpanel" class="tab-pane clearfix" id="listview">





            @foreach($products as $product)

            <div class="productbox clearfix">
                <div class="borderhover">
                <ul class="bxslider-nopager">                                        
                    @if(isset($product->Files) && $product->Files->count()>0)
                        @foreach($product->Files as $file)
                        <li>
                            <a href="#">
                                <?php
                                $condition1 = isset($file) && !empty($file);                            
                                ?>
                                @if($condition1)                                                    
                                    <img src="{{env('APP_URL')}}/images/products/main/{{@$file->path}}" alt="Compare" width="218" height="174">
                                @else
                                    <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="218" height="174">
                                @endif                                                                        
                            </a>
                        </li>                                                            
                        @endforeach
                    @else                                                                                                                        
                        <li>
                            <a href="#">
                                <img src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Compare" width="218" height="174">
                            </a>
                        </li>
                    @endif
                </ul>
                </div>
                <!--<figure class="img"><a href="#"><img src="bootstrap/img/category-218-174-1.jpg" width="218" height="174" alt=""></a></figure> -->
                <div class="productbox-content">
                    <div class="item-detail">
                        <a href="#" class="title">{{$product->name}}</a>
                        <div class="price">
                            <del>
                                <em>
                                    ${{$product->base_price }}  
                                </em>
                            </del>
                            @if(isset($product->productSkusVariantAttribute) && count(@$product->productSkusVariantAttribute)>0)
                                <span>
                                    @foreach($product->productSkusVariantAttribute as $productSkusVariantAttribute)
                                        @if($productSkusVariantAttribute->is_default=="Yes")
                                            ${{ $productSkusVariantAttribute->final_price }}
                                        @endif
                                    @endforeach                                
                                </span>
                            @endif
                        </div>                        
                    </div>
                    <div class="item-footer">
                        <span>Free Shipping</span>
                        <span class="link">
                            <a href="#" title="Favorite" class="favorite">Favorite</a> 
                            <a href="#" title="Cart" class="cart">Cart</a>
                        </span>
                    </div>
                </div>
            </div>


            @endforeach


        </div>
    </div>
</div> 

    
