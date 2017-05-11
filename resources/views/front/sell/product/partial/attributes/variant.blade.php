
<?php $skuCount = 0; $variantValuesArray = $variantAttributeIdsArray = [];?>
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
                                    {!! Form::select('product_sku[0][attributes]['.$attributeOptionKey.']', $attributeOptionValue, null, ['data-attributeidvalue' => $attributeOptionKey, 'data-attributesetidvalue' => $attributeSetKey, 'disabled'=>'disabled', 'class'=>'width150 sku-select-adjust skuVariant'.$attributeOptionKey]) !!}
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
                @foreach($productSkuVariantAttributes['productSkus'] as $productSkuIndex=>$productSku)
                
                <tr id="currentRowNumber{{$skuCount}}" data-skurowid="{{$skuCount}}">
                    <input type="hidden" name="product_sku[{{$skuCount}}][product_sku_id]" id="" value="{{encrypt($productSku['id'])}}" />
                    
                    <td class="default" data-title="Default">
                        <div class="custom-radio">
                            <label for="product_sku[{{$skuCount}}][is_default]">
                                <input class="product_sku_default" id="product_sku[{{$skuCount}}][is_default]" type="radio" name="product_sku[{{$skuCount}}][is_default]" value="{{($productSku['is_default']=='Yes')?'Yes':'No'}}" {{($productSku['is_default']=='Yes')?'checked':''}}/><span></span>
                            </label>
                        </div>
                    </td>
                    <td class="col1" data-title="SKU Label">
                        <input value="{{$productSku['sku']}}" type="text" class="form-control product_sku.{{$skuCount}}.sku" name="product_sku[{{$skuCount}}][sku]" maxlength="50"/>
                    </td>

                    <td class="col2" data-title="Image">
                        <!--<input type="file" class="form-control" name="product_sku[{{$skuCount}}][image]" />-->
                        <span class="basebox addimage" id="variant_image_{{$skuCount}}">
                            <small class="value">Add Image</small>
                            <?php
                            if(isset($productSku['image']) && !empty($productSku['image']))
                            {
                                $old_image  = $productSku['image'];
                                $moduleName = 'products';
                                $path       = public_path() . '/images/' . $moduleName . '/';
                                $small      = $path . '/small/' . $old_image;
                                
                                if(file_exists($small))
                                {
                                    $image = getImageFullPath($old_image, $moduleName, 'small');
                                    echo '<img id="old_variant_image_'.$skuCount.'" width="100" height="100" src="'.$image.'">';
                                    ?>
                                    <input type="file" class="form-control upload_variant_image"  sku_no ="{{$skuCount}}" name="product_sku[{{$skuCount}}][image]" >

                                    <input type="hidden" value="{{$old_image}}" sku_no ="{{$skuCount}}" name="product_sku[{{$skuCount}}][old_image]" >


                                    <?php
                                }
                            }else{ ?>
                                <input type="file" class="form-control upload_variant_image" sku_no ="{{$skuCount}}" name="product_sku[{{$skuCount}}][image]" ><?php
                            }
                            ?>
                        </span>
                    </td>

                    <td class="col3" data-title="Attributes">
                        @foreach($productSku['productVariantAttribute'] as $skuVariant)
                            <?php $variantValuesArray[$productSkuIndex][$skuVariant['attribute_id']]=$skuVariant['attribute_value_id']; ?>
                            <?php $variantAttributeIdsArray[] = $skuVariant['attribute_id']; ?>
                        @endforeach
                    </td>

                    <td class="col5" data-title="Quantity">
                        <label for="sellqty1" class="sr-only">Quantity</label>
                        <input value="{{($productSku['quantity'] > 0)?$productSku['quantity']:''}}" type="text" placeholder="0" class="form-control sku_quantity_tr_cls product_sku.{{$skuCount}}.quantity" id="sellqty1" name="product_sku[{{$skuCount}}][quantity]" maxlength="6"/>
                    </td>

                    <td class="col6" data-title="Additional price">
                        <label for="" class="sr-only">Additional price</label>
                        <input type="text" value="{{($productSku['additional_price'] > 0 )?$productSku['additional_price']:''}}" placeholder="" class="check_numeric additional_price_tr_cls form-control product_sku.{{$skuCount}}.additional_price" name="product_sku[{{$skuCount}}][additional_price]" maxlength="11"/>
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
                        @if($skuCount>0)
                            <span class="removeSkuRow remove-attr" title="Remove"></span>
                        @endif
                    </td>
                </tr>
                <?php $skuCount++; ?>
                @endforeach
            </tbody>
        </table>
    </div>
    </br>
        <span>*Quantity as on "<?php echo date('d M, Y H:i:s'); ?>"</span>
</div>

<div class="row" id="variantConsiderNonvariantForNo" style="display: none;">
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Custom label as SKU</label>
            <div class="col-md-4">
                <input type="text" class="form-control" name="sku_prefix_no" maxlength="30" value="{{$productData['sku_prefix']}}" />
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Quantity</label>
            <div class="col-md-4">
                <input type="text" value="{{($productSku['quantity']>0)?$productSku['quantity']:''}}" class="form-control digit_only_cls" id="quantity" name="quantity_no" maxlength="6"/><span>As on <?php echo date('d M, Y H:i:s'); ?></span>

            </div>
        </div>
    </div>
</div>
</div>

@else
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Custom label as SKU</label>
            <div class="col-md-4">
                <input type="text" class="form-control" name="sku_prefix_no" maxlength="30" value="{{$productData['sku_prefix']}}" />
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Quantity</label>
            <div class="col-md-4">
                <input type="text" value="{{($productSkuVariantAttributes['productSkus'][0]['quantity'] > 0)?$productSkuVariantAttributes['productSkus'][0]['quantity']:''}}" class="form-control digit_only_cls" id="quantity" name="quantity_no" maxlength="6"/>
                <span>As on <?php echo date('d M, Y H:i:s'); ?></span>
            </div>
        </div>
    </div>
</div>
@endif

<?php $GLOBALS['skuCount'] = $skuCount; ?>
<?php $GLOBALS['variantValuesArray'] = json_encode($variantValuesArray); ?>
<?php $GLOBALS['variantAttributeIdsArray'] = json_encode(array_unique($variantAttributeIdsArray)); ?>

@push('scripts')
<script type="text/javascript">
function readURL(input,sku_no) {
    if (input.files)
    {  /* console.log(this);
        return false;*/
      $( input.files ).each(function( ival ) {
        //console.log(input.files[ival]);
        var reader = new FileReader();  
        reader.onload = function (e) {
          //$('.upload_variant_image').attr('src', e.target.result);
            $('#old_variant_image_'+sku_no).remove();
            $('#variant_image_'+sku_no).append('<img width="100" height="100" src="'+e.target.result+'">');
        }
        
        reader.readAsDataURL(input.files[0]);

      });
    }
}
$(".upload_variant_image").on('change',function(){
    var sku_no = $(this).attr('sku_no');
    readURL(this,sku_no);
});
</script>
@endpush