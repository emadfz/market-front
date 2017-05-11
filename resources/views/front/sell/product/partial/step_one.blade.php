<div class="sell-productstep1">
    <h5>Product details</h5>
    <hr>
    <!--Product Add Filter Start-->
    <div class="sell-addproduct">

        @include('front.sell.product.partial.category_select')
        
        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label for="name" class="control-label col-md-2">Product title<span class="required">*</span></label>
                    <div class="col-md-4">
                        {!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'name', 'maxlength'=>100]) !!}
                    </div>
                    <label for="manufacturer" class="control-label col-md-2">Manufacturer<span class="required">*</span></label>
                    <div class="col-md-4">
                        {!! Form::text('manufacturer', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'manufacturer', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>
        </div>

        @include('front.sell.product.partial.description')
        <hr/>
        @include('front.sell.product.partial.mode_of_selling')
        <hr/>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group mrg-bottom">
                    <label class="control-label col-md-2 padd-topnone">Product origin Address<span class="required">*</span></label>
                    <div class="col-md-10"> <span class="mrg-right10">Same as seller's mailing address</span>
                        <div class="custom-radio">
                            <label for="originYes"><input id="originYes" type="radio" value="Yes" name="product_origin"><span></span>Yes</label>
                            <label for="originNo"><input id="originNo" type="radio" value="No" name="product_origin"><span></span>No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('front.sell.product.modal.create_address')

        <hr/>
        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group mrg-bottom">
                    <label class="control-label col-md-2 padd-topnone">Warranty applicable<span class="required">*</span></label>
                    <div class="col-md-10">
                        <div class="custom-radio">
                            <label for="warrantyYes">{!! Form::radio('warranty_applicable', 'Yes', false, ['class'=>'form-control', 'id'=>'warrantyYes']) !!}<span></span>Yes</label>
                            <label for="warrantyNo">{!! Form::radio('warranty_applicable', 'No', false, ['class'=>'form-control', 'id'=>'warrantyNo']) !!}<span></span>No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row warranty-setting" style="display: none;">
            <div class="col-md-12">
                <div class="form-group clearfix">
                    <div class="col-md-10 col-md-offset-2">
                        <label for="warrantyType" class="control-label">Select warranty type</label>
                        <div class="selectbox col-md-4 col-sm-4 col-xs-12">
                            {!! Form::select('warranty_type', $warrantyType, null, ['class'=>'selectpicker', 'id' => 'warrantyType']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group clearfix">
                    <div class="col-md-10 col-md-offset-2">
                        <label for="duration" class="control-label">Duration</label>
                        <div class="col-md-6 col-sm-6 nopadding">
                            <div class="col-md-6 col-sm-6 col-xs-12 mobile-device width100">
                                {!! Form::text('warranty_duration', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'warranty_duration']) !!}
                            </div>
                            <div class="selectbox col-md-6 col-sm-6 col-xs-12">
                                {!! Form::select('warranty_duration_type', $warrantyDurationType, null, ['class'=>'selectpicker', 'id' => 'warranty_duration_type']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group mrg-bottom">
                    <label class="control-label col-md-2 padd-topnone">Return Applicable<span class="required">*</span></label>
                    <div class="col-md-10">
                        <div class="custom-radio">
                            <label for="returnYes">{!! Form::radio('return_applicable', 'Yes', false, ['class'=>'form-control', 'id'=>'returnYes']) !!}<span></span>Yes</label>
                            <label for="returnNo">{!! Form::radio('return_applicable', 'No', false, ['class'=>'form-control', 'id'=>'returnNo']) !!}<span></span>No</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row return-setting" style="display: none;">
            <div class="col-md-12">
                <div class="form-group clearfix">
                    <div class="col-md-10 col-md-offset-2">
                        <label class="control-label">Select days for return acceptance</label>
                        <div class="selectbox col-md-4 col-sm-4 col-xs-12">
                            {!! Form::select('return_acceptance_days', $returnAcceptanceDays, null, ['class'=>'selectpicker', 'id' => 'returnAcceptanceDays']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label for="model" class="control-label col-md-2">Model<span class="required">*</span></label>
                    <div class="col-md-4">
                        {!! Form::text('model', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'model', 'maxlength'=>50]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label for="product_condition_id" class="control-label col-md-2">Product condition<span class="required">*</span></label>
                    <div class="selectbox col-md-4" id="productCondition">
                        {!! Form::select('product_condition_id', $productConditions, null, ['class'=>'form-control selectpicker', 'id' => 'product_condition_id']) !!}
                        <!--style="display:block !important;"-->
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label for="productType" class="control-label col-md-2">Type<span class="required">*</span></label>
                    <div class="selectbox col-md-4">
                        {!! Form::select('product_type', $productType, null, ['class'=>'selectpicker', 'id' => 'productType']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label for="occasion" class="control-label col-md-2">Occasion</label>
                    <div class="selectbox col-md-4">
                        {!! Form::select('occassion_id[]', $occasions, @explode(',',$productData['occassion_id']), ['class'=>'selectpicker', 'id' => 'occasion','multiple'=>true]) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-2 padd-topnone">System Gen. Product ID</label>
                    <div class="selectbox col-md-4">
                        <input type="text" class="form-control" disabled placeholder="{{($productId != '0')?decrypt($productId):'0'}}">
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="form-btnblock text-right nomargin">
            <input type="hidden" value="" name="submit_type" />
            <input type="button" title="Save as draft" class="btn btn-lightgray productSubmitCls" value="Save as draft" />
            <input type="button" title="Next" class="btn btn-primary productSubmitCls" value="Next" />
        </div>
    </div>
    <!--Product Add End-->    
</div>

@push('scripts')
<script type="text/javascript">
    $(document).on("change", "input[name=warranty_applicable]", function () {
        this.value == 'Yes' ? $("div.warranty-setting").show() : $("div.warranty-setting").hide();
    });
    
    @if($updateFlag == TRUE && $productData['warranty_applicable'] == 'No')
        $('input:radio[name="warranty_applicable"]').filter('[value="No"]').attr('checked', true);
        $("div.warranty-setting").hide();
    @else
        $('#warrantyYes').trigger('click');
        /*$('input:radio[name="warranty_applicable"]').filter('[value="Yes"]').attr('checked', true);
        $("div.warranty-setting").show();*/
    @endif
    
    $(document).on("change", "input[name=return_applicable]", function () {
        this.value == 'Yes' ? $("div.return-setting").show() : $("div.return-setting").hide();
    });

    @if($updateFlag == TRUE && $productData['return_applicable'] == 'No')
        $('input:radio[name="return_applicable"]').filter('[value="No"]').attr('checked', true);
        $("div.return-setting").hide();
    @else
        $('input:radio[name="return_applicable"]').filter('[value="Yes"]').attr('checked', true);
        $("div.return-setting").show();
    @endif
    
    $(document).on("change", "input[name=product_origin]", function () {
        this.value == 'No' ? $("#productOriginAddressId").show() : $("#productOriginAddressId").hide();
    });
    
    @if($updateFlag == TRUE && !empty($productData['productOriginAddress']))
        $('input:radio[name="product_origin"]').filter('[value="No"]').attr('checked', true);
        $("div#productOriginAddressId").show();
    @else
        $('input:radio[name="product_origin"]').filter('[value="Yes"]').attr('checked', true);
    @endif
    
    $('.productSubmitCls').click(function(){
        if($('#mosBuyNowAuction').is(':checked'))
        {
            if($('#bin_auction__by_price').is(':checked'))
            {
                var base_price          = $("#bin_auction\\.base_price").val();
                var max_product_price   = $('#bin_auction_by_price\\.max_product_price').val();
                
                if( base_price !='' && base_price != 'undefined' )
                {   
                    if(max_product_price < base_price)
                    {   
                         if (confirm('Are you sure? Your Max Product Price is lower than Base Price') == false) {
                            return false;
                         }
                    }
                }
            }
        }
        
        $("input[name=submit_type]").val($(this).val());
        CKupdate();
        $('form[id=productFormSubmitId]').submit();
    });
    
    function CKupdate(){
        for ( instance in CKEDITOR.instances )
            CKEDITOR.instances[instance].updateElement();
    }
</script>

<script src="http://cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script> 
<script>
CKEDITOR.replace("ckeditor", {
    uiColor: "#F5F5F5",
    toolbar: "standard",
    toolbarLocation: 'bottom',
    scayt_autoStartup: false,
    enterMode: CKEDITOR.ENTER_BR,
    resize_enabled: true,
    disableNativeSpellChecker: false,
    htmlEncodeOutput: false,
    height: 140,
    removePlugins: 'elementspath',
    editingBlock: false,
    toolbarGroups:[{"name": "basicstyles", "groups": ["basicstyles", "cleanup"]},{"name": "links", "groups": ["links"]},{"name": "document", "groups": ["mode"]}],
    removeButtons: 'Source,RemoveFormat,Superscript,Anchor,Styles,Specialchar'
});
</script>
@endpush