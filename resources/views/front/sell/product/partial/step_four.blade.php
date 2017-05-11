<div class="sell-productstep4">
    <h5>Promotions</h5>
    <hr>
    <h5 class="blacktitle">Related Products</h5>
    <div class="sell-addproduct">
        <div class="row">
            <div class="form-horizontal col-md-12">
                <!--            <div class="form-group">
                                <label class="control-label col-md-2">Show Help Content</label>
                                <div class="col-md-4">
                                    <div class="attrfield">
                                        <div class="vertical custom-checkbox">
                                            <label>
                                                <input type="checkbox" value="" checked="">
                                                <span></span>Size Chart</label>
                                        </div>
                                    </div>
                                </div>
                            </div>-->
                <div class="form-group flex-wrap">
                    <label class="control-label col-md-2">Related products</label>
                    <div class="col-md-4">
                        <div class="attrfield">
                            <div class="vertical custom-checkbox">

                                @foreach($allRelatedProductIds as $productInfo)
                                <?php
                                $checked = '';
                                if(in_array($productInfo['id'], $selectedRelatedProductIds)){
                                    $checked = 'checked';
                                }
                                ?>
                                <label>
                                    <input {{$checked}} type="checkbox" value="{{$productInfo['id']}}" name="related_product_ids[]">
                                    <span></span>{{$productInfo['name']}}
                                </label>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <!--Help and Related Products End-->
                <hr>
                <!--Promotions Start-->
                <h5 class="blacktitle">Promotions</h5>
                <div class="form-group">
                    <label class="control-label col-md-2 padd-topnone">Applicable for promotion</label>
                    <div class="col-md-10">
                        <div class="custom-radio">
                            <label for="promotionsyes">
                                {!! Form::radio('promotion_applicable', 'Yes', false, ['class'=>'form-control', 'id'=>'promotionsyes']) !!}<span></span>Yes
                            </label>
                            <label for="promotionsno">
                                {!! Form::radio('promotion_applicable', 'No', false, ['class'=>'form-control', 'id'=>'promotionsno']) !!}<span></span>No
                            </label>
                        </div>
                    </div>
                </div>
                <!--Promotions End-->
            </div>
        </div>
        <div class="form-btnblock text-right nomargin"> 
            <input type="hidden" value="" name="submit_type" />
            <input type="button" title="Save as draft" class="btn btn-lightgray productSubmitCls" value="Save as draft" />
            <a href="{{route('createProduct',['step_three', $productId])}}" title="Previous" class="btn btn-lightgray">Previous</a>
            <input type="button" title="Preview & Publish" class="btn btn-primary productSubmitCls" value="Preview & Publish" />
        </div>
        
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    $('.productSubmitCls').click(function () {
        $("input[name=submit_type]").val($(this).val());
        $('form[id=productFormSubmitId]').submit();
    });
</script>
@endpush