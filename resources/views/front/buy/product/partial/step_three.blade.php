<div class="sell-productstep3">
    <h5>Pricing, Quantity & Shipping details</h5>
    <hr>
    <h5 class="blacktitle">Quantity & Pricing details</h5>

    <div class="row">
        <div class="form-horizontal col-md-12">
            <div class="form-group">
                <label class="control-label col-md-2">Product Listing Price</label>
                <div class="col-md-4">
                    <input type="text" class="form-control check_numeric" name="product_listing_price" maxlength="11" value="{{$productData['base_price']}}" readonly/>
                </div>
            </div>
        </div>
    </div>
    <!--Advance Filter Start--> 
    <div class="advance-filter clearfix">
        <div class="form-group">
            <label class="control-label padd-topnone mrg-right10">Is there any variation on the basic of selected attributes?</label>
            <div class="custom-radio">
                <label for="variationYes">
                    <input id="variationYes" type="radio" value="Yes" name="variation_allowed" {{($variantAttrAllowed == FALSE || empty($attributeVariantOptions)) ? 'disabled="disabled"' : ''}} />
                           <span></span>Yes </label>
                <label for="variationNo">
                    <input id="variationNo" type="radio" value="No" name="variation_allowed" checked="checked"/>
                    <span></span>No </label>
            </div>
        </div>

        @if($variantAttrAllowed == TRUE && empty($attributeVariantOptions))
        <div class="form-group has-error clearfix">
            <span class="help-block error">Variant attributes have not been found for the selected category from system!</span>
        </div>
        @else
        <div class="attributesVariations" style="display: none;">
            <hr>
            <h4>Create a list of all the variations of your items</h4>
            <ul class="variations">
                <li>The list below is based on the variation details you defined.</li>
                <li>If the list includes a variation you don't have in stock,click Remove.</li>
                <li>If the list does not include an item you have in stock,click Add.</li>
                <li>Once the list includes all the variations you're selling, click Continue.</li>
            </ul>
        </div>
        @endif
    </div>
    <!--Advance Filter End-->

    <!-- START: Variant attributes -->    
    @include('front.sell.product.partial.attributes.variant')
    <!-- END: Variant attributes -->    
    <hr>
    <!-- START: Non variant attributes -->    
    @include('front.sell.product.partial.attributes.non_variant')
    <!-- END: Non variant attributes -->         
    <hr>
    
    <!--Product Add Shipping Start-->
    <h5 class="blacktitle">Shipping details</h5>

    <div class="sell-addproduct">
        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group parcel-attr">
                    <label class="control-label col-md-2 padd-topnone">Parcel dimensions (L*W*H)</label>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Length" name="parcel_dimension_length" />
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Width" name="parcel_dimension_width" />
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" placeholder="Height" name="parcel_dimension_height" />
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-2">Length class</label>
                    <div class="col-md-4">
                        <div class="selectbox">
                            {!! Form::select('length_class', $productShippingLengthClass, null, ['class'=>'selectpicker']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group parcel-attr">
                    <label class="control-label col-md-2">Parcel weight</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Parcel weight" name="parcel_weight" />
                    </div>
                    <label class="control-label col-md-2">Weight class</label>
                    <div class="col-md-4">
                        <div class="selectbox">
                            {!! Form::select('weight_class', $productShippingWeightClass, null, ['class'=>'selectpicker']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group mrg-bott10">
                    <label class="control-label col-md-2 padd-topnone">Select shipping</label>
                    <div class="col-md-10">
                        <div class="custom-radio">
                            <label for="shippingby">
                                <input id="shippingby" type="radio" name="shipping_type" value="Marketplace" checked /><span></span>Shipping by Inspree
                            </label>
                            <label for="shippingseller">
                                <input id="shippingseller" type="radio" name="shipping_type" value="Seller" /><span></span>Seller's Shipping
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group parcel-attr">
                    <div class="col-md-10 col-md-offset-2 mobile-device">
                        <a href="#shippingrates" data-toggle="modal" class="btn btn-primary" title="View Shipping Rates">view shipping rates</a> 
                        <a href="#" class="btn btn-primary" title="Enter Shipping Rates">enter shipping rates</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="form-btnblock text-right nomargin"> 
            <input type="hidden" value="" name="submit_type" />
            <input type="button" title="Save as draft" class="btn btn-lightgray productSubmitCls" value="Save as draft" />
            <a href="{{route('createProduct',['step_two', $productId])}}" title="Previous" class="btn btn-lightgray">Previous</a>
            <input type="button" title="Next" class="btn btn-primary productSubmitCls" value="Next" />
        </div>
    </div>
    <!--Product Add Shipping End--> 
</div>

@push('modalPopup')
@include('front.sell.product.modal.shipping_rates')
@endpush

@push('styles')
<style type='text/css'>.advance-filter{padding-bottom: 12px;}.advance-filter .form-group{margin-bottom:0px;}.sku-select-adjust {margin-bottom: 5px;}.nonvarattrset-cls{border-bottom: 1px dotted #dedede;text-align: left !important;margin-bottom: 5px;font: bold;}</style>
@endpush

@push('scripts')
<script type="text/javascript">

    $(document).on("keyup, keydown", "input[name=sku_prefix]", function () {
        ($.trim($(this).val()) != "") ? $("#create_variations").removeAttr("disabled") : $("#create_variations").attr("disabled", "disabled");
    });

    /* when you click on create variation then update that value to each row of table tr */
    $(document).on("click", "#create_variations", function () {
        var customLabelSku = $("input[name=sku_prefix]").val();
        $("table#productSKUs tr td.col1").find("input[type=text]").val(customLabelSku);
    });

    /*--- START: ATTRIBUTE SKU- Variation allowed check uncheck ---*/
    $(document).on("change", "input[name=variation_allowed]", function () {
        if (this.value == 'Yes') {
            $("div.attributesVariations").show().css({'margin-bottom': '15px'});
            $("div#variantConsiderNonvariant").show();
        } else {
            $("div.attributesVariations").hide();
            $("div#variantConsiderNonvariant").hide();
        }
    });
    /*--- END: ATTRIBUTE SKU- Variation allowed check uncheck ---*/

    /*--- START: ATTRIBUTE SKU- Variant attribute check uncheck ---*/
    var maxPossibleSkuCombination = 1;
    @if($updateFlag == TRUE && $GLOBALS['skuCount'] > 1)
        maxPossibleSkuCombination = "{{$GLOBALS['skuCount']}}";
    @endif
    
    var newRowIndexCheck = 1;
    $(".variantAttrCheckUncheck").on("change", function () {
        var attrSkuThisCheckedId = $(this).val();
        var skuTargetedSelectTemp = "select.skuVariant" + attrSkuThisCheckedId;

        if ($(this).is(":checked")) {
            maxPossibleSkuCombination = maxPossibleSkuCombination * parseInt($("div.attributeCheckCls select.skuVariant" + attrSkuThisCheckedId + " option").length);
            var newContent = $(this).closest("label").next("div.attributeCheckCls").html();
            
            $("table#productSKUs tbody tr").each(function (index, element) {
                var curRowCounter = $(this).data("skurowid");
                var newSelectName = $(newContent).find("select").attr("name").replace('product_sku[0]', 'product_sku[' + curRowCounter + ']');
                var newAttrSetId = $(newContent).find("select").data("attributeoptionvalue");
                var newSelectContent =  $(newContent).find("select").attr("name", newSelectName).parent().html();
                
                var ttttmp = newSelectName.replace('product_sku[' + curRowCounter + '][attributes]', 'product_sku[' + curRowCounter + '][attribute_set_id]');
                
                
                $(this).find("td.col3").append('<div class="cssselect">'
                        +newSelectContent+
                        '<input type="hidden" value="'+newAttrSetId+'" name="'+ttttmp+'" />'
                        +'</div>');
                
                $(this).find("select").each(function (index, iElem) {
                    $(this).next("[type=hidden]").attr('value',$(this).data('attributeoptionvalue'));
                });
            });

            $("div#variantConsiderNonvariant").find(skuTargetedSelectTemp).closest("div.form-group").css({'display': 'none'});
        } else {
            /*maxPossibleSkuCombination -= $(skuTargetedSelectTemp+' option').length;*/

            $("table#productSKUs tr td.col3").find(skuTargetedSelectTemp).closest("div.cssselect").remove();
            $("div#variantConsiderNonvariant").find(skuTargetedSelectTemp).closest("div.form-group").css({'display': 'block'});
        }
    });
    /*--- END: ATTRIBUTE SKU- Variant attribute check uncheck ---*/


    /*--- START: ATTRIBUTE SKU- Add more variant to generate sku ---*/
    $(document).on("click", "table#productSKUs .addMoreSku", function () {
        if (newRowIndexCheck >= maxPossibleSkuCombination) {
            toastr.error("According to selection of variant attributes, you are limited to create maximum possible SKU: " + maxPossibleSkuCombination, "SKU maximum limit exceed!!", {timeOut: 10000});
            return false;
        }

        /*-- clone table tr and change name and id as per requirement --*/
        $("table#productSKUs tbody tr").eq(0).clone().each(function (trIdx, trElem) {
            var $tr = $(trElem);
            var newRowIndex = ($("table#productSKUs tr").length) - 1;
            newRowIndexCheck = ($("table#productSKUs tr").length);
            $tr.attr('id', 'currentRowNumber' + newRowIndex);
            $tr.attr('data-skurowid', newRowIndex);
        
            $tr.find("input, select, textarea").each(function (index, iElem) {//:not([type=hidden])
                var $input = $(iElem);
                $input.attr({id: function (_, id) {
                            if ($input.attr("type") == 'radio') {
                                return id.replace('product_sku[0]', 'product_sku[' + newRowIndex + ']');
                            }
                        }, name: function (_, id) {
                            return id.replace('product_sku[0]', 'product_sku[' + newRowIndex + ']');
                        }, value: ''});/*if($input.hasClass('dateControl')) {$input.val("");}*/
                if ($input.attr("type") == 'radio') {
                    $(this).closest("label").attr("for", $(this).attr("id"));
                }
            });
            
            $tr.find("select").each(function (index, iElem) {
                $(this).next("[type=hidden]").attr('value',$(this).data('attributeoptionvalue'));
            });
            
            $tr.find(".addMoreSku").after('<span class="removeSkuRow remove-attr" title="Remove"></span>');
        }).appendTo("table#productSKUs");
    });
    /*--- END: ATTRIBUTE SKU- Add more variant to generate sku ---*/

    /*--- START: ATTRIBUTE SKU- Remove sku ---*/
    $(document).on("click", "table#productSKUs .removeSkuRow", function () {
        $(this).closest("tr").remove();
        newRowIndexCheck--;
    });
    /*--- END: ATTRIBUTE SKU- Remove sku ---*/

    /*--- Common function to update price and display as final price ---*/
    function productAttributePriceUpdate() {
        $("table#productSKUs tbody tr").each(function (index, element) {
            var cur_additional_price = $(this).find("td .additional_price_tr_cls").val();
            if ($.trim(cur_additional_price) == "") {
                $(this).find("td .additional_price_tr_cls").val(0);
                cur_additional_price = 0;
            }
            var finalPrice = 0;
            finalPrice = (parseFloat(cur_additional_price) + parseFloat($('input[name=product_listing_price]').val())).toFixed(2);

            if (finalPrice != 'NaN') {
                $(this).find("td .additional_price_tr_cls").closest("td").next("td").find('.final_price_tr_cls').val(finalPrice);
            }
        });
    }

    /*--- On keyup of additional price field and product listingg price field ---*/
    $(document).on('keyup', ".additional_price_tr_cls, input[name=product_listing_price]", function () {
        productAttributePriceUpdate();
    });
    
    @if($updateFlag == TRUE && $productData['variation_allowed'] == 'Yes')
        $("input[name=variation_allowed][value=Yes]").trigger("click");
    @endif
</script>
@endpush
@push('scripts')
<script type="text/javascript">
    $('.productSubmitCls').click(function () {
        $("input[name=submit_type]").val($(this).val());
        $('form[id=productFormSubmitId]').submit();
    });
</script>
@endpush