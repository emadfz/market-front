<?php

/* 
 * To get product for Department listing page
 */
?>

@foreach($products_chunk as $index=>$products)
                            @if($index==0)
                                <div class="category-view ">           
                            @else
                                <div class="category-view reverse">           
                            @endif                	

                            <?php 
                                if($index==0){
                                    $boxData=array(
                                        array('box_type'=>'large-height','width'=>'400','height'=>'535','str_limit'=>'15'),
                                        array('box_type'=>'large-width','width'=>'559','height'=>'229','str_limit'=>'30'),
                                        array('box_type'=>'width-col2','width'=>'275','height'=>'194','str_limit'=>'5'),
                                        array('box_type'=>'width-col2','width'=>'275','height'=>'194','str_limit'=>'5'),
                                        array('box_type'=>'cat-col2','width'=>'480','height'=>'227','str_limit'=>'20'),
                                        array('box_type'=>'cat-col2','width'=>'480','height'=>'227','str_limit'=>'20'),
                                        array('box_type'=>'cat-col3','width'=>'316','height'=>'227','str_limit'=>'10'),
                                        array('box_type'=>'cat-col3','width'=>'316','height'=>'227','str_limit'=>'10'),
                                        array('box_type'=>'cat-col3','width'=>'316','height'=>'227','str_limit'=>'10'),
                                    );
                                }
                                else{
                                    $boxData=array(
                                        array('box_type'=>'large-height','width'=>'400','height'=>'535','str_limit'=>'15'),
                                        array('box_type'=>'width-col2','width'=>'275','height'=>'194','str_limit'=>'5'),
                                        array('box_type'=>'width-col2','width'=>'275','height'=>'194','str_limit'=>'5'),
                                        array('box_type'=>'large-width','width'=>'559','height'=>'229','str_limit'=>'30'),                                        
                                        array('box_type'=>'cat-col2','width'=>'480','height'=>'227','str_limit'=>'20'),
                                        array('box_type'=>'cat-col2','width'=>'480','height'=>'227','str_limit'=>'20'),
                                        array('box_type'=>'cat-col3','width'=>'316','height'=>'227','str_limit'=>'10'),
                                        array('box_type'=>'cat-col3','width'=>'316','height'=>'227','str_limit'=>'10'),
                                        array('box_type'=>'cat-col3','width'=>'316','height'=>'227','str_limit'=>'10'),
                                    );
                                }
                                $key=-1;
                            ?>
                            @foreach($products as $product)                                                    
                                    <div class="brick {{$boxData[++$key]['box_type']}}">
                                        <div class="cat-image">
                                            <a href="{{URL('product/'.$product->product_slug)}}">                                                                        
                                                <?php 
                                                    $condition1=isset($product->Files[0]) && !empty($product->Files[0]->path); 
                                                ?>
                                                @if($condition1)
                                                    <img title="{{$product->name}}" src="{{env('APP_URL')}}/images/products/main/{{@$product->Files[0]->path}}" alt="{{@$product->name}}" width="{{$boxData[$key]['width']}}" height="{{$boxData[$key]['height']}}">
                                                @else
                                                    <img title="{{$product->name}}" src="{{env('APP_ADMIN_URL')}}/assets/admin/layouts/layout4/img/no-image-main.png" alt="Category" width="{{$boxData[$key]['width']}}" height="{{$boxData[$key]['height']}}">
                                                @endif    
                                            </a>
                                        </div>
                                        <div class="cat-bar">
                                            <div class="cat-outer mrg-bott5">
                                                <h3>
                                                    <a href="{{URL('product/'.$product->product_slug)}}" title="{{$product->name}}">{{str_limit($product->name,$boxData[$key]['str_limit'])}}</a>
                                                </h3>
                                                <span class="price">
                                                    <del>
                                                        <em>
                                                           <?php print(convert_currency( formatPrice(@$product->base_price), session()->get('currency_rate')));?>                                                            
                                                        </em>
                                                    </del>
                                                    <span>
                                                        @foreach($product->productSkusVariantAttribute as $productSkusVariantAttribute)
                                                            @if($productSkusVariantAttribute->is_default=="Yes")
                                                                {{convert_currency( $productSkusVariantAttribute->final_price, session()->get('currency_rate'))}}
                                                            @endif
                                                        @endforeach                                                                                        
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="cat-outer">
                                                <div class="cat-star"><img src="{{asset('assets/front/img/star.png')}}"> <span>5 Review</span></div>
                                                <div class="link">
                                                    <a href="#" title="Favorite" data-productid="{{encrypt($product->id)}}" class="compare-icon">Compare</a>
                                                    <a href="#" title="Favorite" class="favorite">Favorite</a> 
                                                    <a href="#" title="Cart" class="cart">Cart</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                        
                            @endforeach                        
                        </div>
                        @endforeach       