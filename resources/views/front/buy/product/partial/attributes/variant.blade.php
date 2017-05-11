<?php $skuCount = 0;?>
@if($variantAttrAllowed == TRUE && !empty($attributeVariantOptions))
<div class="attributesVariations" style="display: none;">
    <div class="form-horizontal col-md-12 clearfix">
        <div class="form-group flex-wrap">
            <label class="control-label col-md-2">Select attribute</label>
            <div class="col-md-9">
                <div class="attrfield" id="variantAttrMainDivId">
                    <div class="vertical custom-checkbox">
                        <?php $q = 0; ?>
                        @foreach($attributeVariantOptions as $attributeSetKey => $attributeVariantOption)
                            <span class="col-md-9 nonvarattrset-cls attr-set-title">{{\App\Models\AttributeSet::find($attributeSetKey)->attribute_set_name}}</span>
                            
                            @foreach($attributeVariantOption as $attributeOptionKey => $attributeOptionValue)
                            <label class="col-md-4" style="margin-top: 5px;">
                                <input name="variant_attributes[]" class="variantAttrCheckUncheck" id="checkVariant{{$attributeOptionKey}}" type="checkbox" value="{{$attributeOptionKey}}"> <span></span>{{key($attributeOptionValue)}}
                            </label>
                            <div class="col-md-5 attributeCheckCls" style="display: block;">
                                <div class="cssselect">
                                    {!! Form::select('product_sku[0][attributes]['.$attributeOptionKey.']', $attributeOptionValue, null, ['data-attributeoptionvalue' => $attributeSetKey, 'class'=>'width150 sku-select-adjust skuVariant'.$attributeOptionKey]) !!}
                                </div>
                            </div>
                            <?php $q++; ?>
                            @endforeach
                            
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Main Filter Start-->
    <div class="main-filter clearfix"> 
        <span class="title">Custom label as SKU</span>
        <input type="text" class="form-control" name="sku_prefix" id="custom_label_sku" value="{{$productData['sku_prefix']}}"/>
        <a {{@$productData['sku_prefix']==""?'disabled':''}} href="javascript:;" title="Create Variations" class="btn btn-primary" id="create_variations">create variations</a> 
    </div>
    <!--Main Filter End--> 

    <div class="table-responsive">
        <table class="table sell-qtypritable table-bordered mobile-table" id="productSKUs">
            <thead>
                <tr>
                    <th class="default"></th>
                    <th class="col1">SKU label</th>
                    <th class="col2">Image</th>
                    <th class="col3">Attributes</th>
                    <th class="col5">Quantity</th>
                    <th class="col6">Additional price increment ($)</th>
                    <th class="col7">Final price ($)</th>
                    <th class="col8">Available in bulk?</th>
                    <th class="col9"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($productSkuVariantAttributes['productSkus'] as $productSku)
                
                <tr id="currentRowNumber{{$skuCount}}" data-skurowid="{{$skuCount}}">
                    <td class="default" data-title="Default">
                        <div class="custom-radio">
                            <label for="product_sku[{{$skuCount}}][default]">
                                <input id="product_sku[{{$skuCount}}][default]" type="radio" name="product_sku_default" value="Yes" {{($productSku['is_default']=='Yes')?'checked':''}}/><span></span>
                            </label>
                        </div>
                    </td>
                    <td class="col1" data-title="SKU Label">
                        <input value="{{$productSku['sku']}}" type="text" class="form-control product_sku.{{$skuCount}}.sku" name="product_sku[{{$skuCount}}][sku]" maxlength="50"/>
                    </td>

                    <td class="col2" data-title="Image">
                        <!--<input type="file" class="form-control" name="product_sku[{{$skuCount}}][image]" />-->
                        <span class="basebox addimage">
                            <input type="file" class="form-control" name="product_sku[{{$skuCount}}][image]" >
                            <small class="value">Add Image</small>
                        </span>
                    </td>

                    <td class="col3" data-title="Attributes">
                        @foreach($productSku['productVariantAttribute'] as $skuVariant)
                        {{$skuVariant['attribute_id']}}:{{$skuVariant['attribute_value_id']}}<br/>
                        @endforeach
                    </td>

                    <td class="col5" data-title="Quantity">
                        <label for="sellqty1" class="sr-only">Quantity</label>
                        <input value="{{$productSku['quantity']}}" type="text" value="" placeholder="0" class="form-control sku_quantity_tr_cls product_sku.{{$skuCount}}.quantity" id="sellqty1" name="product_sku[{{$skuCount}}][quantity]" maxlength="6"/>
                    </td>

                    <td class="col6" data-title="Additional price">
                        <label for="" class="sr-only">Additional price</label>
                        <input type="text" value="{{$productSku['additional_price']}}" placeholder="0" class="check_numeric additional_price_tr_cls form-control product_sku.{{$skuCount}}.additional_price" name="product_sku[{{$skuCount}}][additional_price]" maxlength="11"/>
                    </td>

                    <td align="center" class="col7" data-title="Final price ($)">
                        <input type="text" disabled value="{{$productSku['additional_price']+$productData['base_price']}}" placeholder="0" size="10" class="final_price_tr_cls" name="product_sku[{{$skuCount}}][final_price]" maxlength="11"/>
                    </td>

                    <td class="col8" data-title="Available in bulk?">
                        <div class="vertical custom-radio">
                            <label for="product_sku[{{$skuCount}}][available_in_bulk][Yes]">
                                <input class="avail-bulk bulk_available" type="radio" name="product_sku[{{$skuCount}}][available_in_bulk]" id="product_sku[{{$skuCount}}][available_in_bulk][Yes]" value="Yes" {{$productSku['available_in_bulk'] == 'Yes' ? 'checked' : ''}} /><span></span>Yes
                            </label>
                            <label for="product_sku[{{$skuCount}}][available_in_bulk][No]">
                                <input class="avail-bulk bulk_available" type="radio" name="product_sku[{{$skuCount}}][available_in_bulk]" id="product_sku[{{$skuCount}}][available_in_bulk][No]" value="No" {{$productSku['available_in_bulk'] == 'No' || $productSku['available_in_bulk'] == '' ? 'checked' : ''}} /><span></span>No
                            </label>
                        </div>
                    </td>
                    <td class="col9" data-title="Add/Remove">
                        <span class="addicon-attr addMoreSku" title="Add"></span>
                        @if($skuCount>1)
                            <span class="removeSkuRow remove-attr" title="Remove"></span>
                        @endif
                    </td>
                </tr>
                <?php $skuCount++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Custom label as SKU</label>
            <div class="col-md-4">
                <input type="text" class="form-control" name="sku_prefix" maxlength="30" value="{{$productData['sku_prefix']}}" />
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Quantity</label>
            <div class="col-md-4">
                <input type="text" value="" class="form-control digit_only_cls" id="quantity" name="quantity" maxlength="6"/>
            </div>
        </div>
    </div>
</div>
@endif
<?php $GLOBALS['skuCount'] = $skuCount; ?>