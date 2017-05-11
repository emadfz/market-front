@if($attributeSetCategories->count())
@foreach($attributeSetCategories as $attributeSetCategory)
@foreach($attributeSetCategory->Attributes as $attribute)
{{@$requestdata[$attribute->attribute_name]}}
<div class="panel">
    <h4><a role="button" data-toggle="collapse" data-parent="#accordion" href="#modalrange">{{$attribute->attribute_name}}</a></h4>
    <div id="modalrange" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body">
            <div class="btn-group-vertical custom-checkbox" data-toggle="buttons">
                @foreach($attribute->AttributeValues as $attributeValues)
                @if(isset($requestdata[$attribute->attribute_slug]))  
                    @if(@$requestdata[$attribute->attribute_slug]==$attributeValues->attribute_values)
                        <label class="btn active">
                            {{ $attributeValues->attribute_values }}
                            <input name="attribute_filters[]" class='attribute_filters' type="checkbox" value="{{ $attribute->attribute_slug.'='.$attributeValues->attribute_values }}" checked  style="position:relative" >
                            <span></span>
                        </label>
                    @endif
                @else
                        <label class="btn active">
                            {{ $attributeValues->attribute_values }}
                            <input name="attribute_filters[]" class='attribute_filters' type="checkbox" value="{{ $attribute->attribute_slug.'='.$attributeValues->attribute_values }}" style="position:relative" >
                            <span></span>
                        </label>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endforeach
@endforeach
@endif

<div class="panel">
    <h4><a role="button"  data-toggle="collapse" data-parent="#accordion" href="#pricerange">Price Range</a></h4>
    <div id="pricerange" class="panel-collapse collapse in" role="tabpanel">
        <div class="panel-body">
            <div class="btn-group-vertical custom-checkbox" data-toggle="buttons">                                            
                @foreach(config('project.pricings_array') as $pricing_array)
                        <?php $checked = ''; ?>
                        @if(
                            @$requestdata['from']==$pricing_array['from']
                            &&
                            @$requestdata['to']==$pricing_array['to']
                        )
                        <?php $checked = 'checked'; ?>
                    @endif
                    <label class="btn">
                        <input class='price_filter' data-from="{{$pricing_array['from']}}" data-to="{{$pricing_array['to']}}" type="checkbox" {{$checked}} value="">{{ '$'.$pricing_array['from'].'-$'.$pricing_array['to']}}
                        <span></span>
    <!--                                                    <span>(1,50,000)</span>-->
                    </label>
                @endforeach                                            
            </div>
            <div class="price-input clearfix">
                <div class="form-group clearfix">
                    <label for="fromprice">$</label>
                    <input  type="text" class="form-control width50" id="fromprice" value="{{@$requestdata['from']}}">
                    <label for="toprice">to $</label>
                    <input type="email" class="form-control width50" id="toprice" value="{{@$requestdata['to']}}">
                </div>
                <div class="form-group clearfix">
                    <button type="button" id="go" class="btn btn-primary" >go</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
 $('#go').click(function(){
     $('.attribute_filters').trigger('change');
 });
 $('.price_filter').change(function(){
     //$('.price_filter').attr('checked',false);     
     $('#fromprice,#toprice').val('');
     $(this).prop( "checked" );
     if($(this).is(':checked')){
        $('#fromprice').val($(this).data('from'));
        $('#toprice').val($(this).data('to'));
     }
     
 });
 
 
 $('.attribute_filters').change(function(){     
     url=window.location.pathname+'?';
     parameters=[];
     pricing=[];
     $('.attribute_filters:checked').each(function(){
         parameters.push($(this).val());
     });
     
     if( $('#fromprice').val() !='' ){
         pricing.push('from='+$('#fromprice').val());
     }
     if( $('#toprice').val() !='' ){
         pricing.push('to='+$('#toprice').val());
     }     
     
        
     if(pricing.length>0){
        url=url+pricing.join('&');        
        if(parameters.length>0){
            url=url+'&';
        }
     }
     
     if(parameters.length>0){
        url=url+parameters.join('&');
     }     
     window.location.href=url;
});
 
 
  </script>
@endpush