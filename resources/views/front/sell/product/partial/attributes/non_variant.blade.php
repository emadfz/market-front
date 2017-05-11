<h5 class="blacktitle">Select Other attributes value</h5>

<div class="row">
    <div class="form-horizontal col-md-12">
        <!--<label class="control-label col-md-2 padd-topnone">Select Other attributes value<span class="required">*</span></label>-->
        @if(empty($attributeNonVariantOptions))
        <div class="form-group has-error clearfix">
            <span class="help-block error">Non-variant attributes have not been found for the selected category from system!</span>
        </div>
        @else
        @foreach($attributeNonVariantOptions as $attributeSetKey => $attributeNonVariantOption)
        <div class="form-group">
            <div class="control-label col-md-8 col-md-offset-1 nonvarattrset-cls attr-set-title">
                {{\App\Models\AttributeSet::find($attributeSetKey)->attribute_set_name}}
            </div>
        </div>

        @foreach($attributeNonVariantOption as $type => $attributeOptions)
        
        <?php $nonVarCount = 0;?>
        
        @foreach($attributeOptions as $attributeOptionKey => $attributeOptionValue)

        <div class="form-group">
            @if($type == 'dropdown')
            <label class="control-label col-md-2 col-md-offset-2">{{key($attributeOptionValue)}}</label>
            <div class="selectbox col-md-4">
                {!! Form::select('nonvariant_attributes['.$attributeOptionKey.']', $attributeOptionValue, @$productNonVariantAttributes[$nonVarCount]['attribute_value'], ['class'=>'selectpicker nonvariant_attributes.'.$attributeOptionKey]) !!}
            </div>
            @elseif($type == 'radio')
            <label for="nonvariant_attributes[{{$attributeOptionKey}}]" class="control-label col-md-2 col-md-offset-2">{{key($attributeOptionValue)}}</label>
            <div class="custom-radio">
                @foreach($attributeOptionValue[key($attributeOptionValue)] as $k => $v)
                <label for="nonvariant_attributes1[{{$k}}]" class="control-label col-md-2 col-md-offset-2">
                    <input class="" type="radio" name="nonvariant_attributes[{{$attributeOptionKey}}]" id="nonvariant_attributes1[{{$k}}]" value="{{$k}}" /><span></span>{{$v}}
                </label>
                @endforeach
            </div>
            @else
            <label class="control-label col-md-2 col-md-offset-2">{{$attributeOptionValue}}</label>
            <div class="col-md-4">
                {!! Form::text('nonvariant_attributes['.$attributeOptionKey.']', @$productNonVariantAttributes[$nonVarCount]['attribute_value'], ['class'=>'form-control nonvariant_attributes.'.$attributeOptionKey]) !!}
            </div>
            @endif
            <input type="hidden" value="{{$attributeSetKey}}" name="nonvariant_attribute_set_id[{{$attributeOptionKey}}]" />
        </div>
        
        <?php $nonVarCount++;?>
        @endforeach
        @endforeach
        @endforeach
        @endif
    </div>
</div>

<div class="row" id="variantConsiderNonvariant" style="display: none;">
    <div class="form-group" style="padding-bottom: 20px;">
        <div class="control-label col-md-8 col-md-offset-1 nonvarattrset-cls">
            Variant attribute to be considered as non-variant attribute
        </div>
    </div>
    <div class="form-horizontal col-md-12">
        @foreach($attributeVariantOptions as $attributeSetKey => $attributeVariantOption)
            @foreach($attributeVariantOption as $attributeOptionKey => $attributeOptionValue)
            <div class="form-group">    
                <label class="control-label col-md-2 col-md-offset-2">{{key($attributeOptionValue)}}</label>
                <div class="selectbox col-md-4">
                    {!! Form::select('nonvariant_attributes['.$attributeOptionKey.']', $attributeOptionValue, null, ['class'=>"nonvariant_attributes".$attributeOptionKey." selectpicker skuVariant".$attributeOptionKey]) !!}
                    <input type="hidden" value="{{$attributeSetKey}}" name="nonvariant_attribute_set_id[{{$attributeOptionKey}}]" />
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</div>