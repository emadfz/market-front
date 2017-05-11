<!--Rightside Start -->
<div class="rightcol-bg clearfix">
<div class="equal-column">   
  @if($product_type=='preview')
    <h4>Add New Product (Set a Preview)</h4>
  @else
    <h4>Add New Classified Product (Set a Preview)</h4>
  @endif
  <div class="sell-addproduct">
 		<div class="row">
     <!--  <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Listing Status</label>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Draft">
          </div>
        </div>
      </div> -->
  </div>
  	<hr>
      <h5 class="blacktitle">General Details</h5>
      <div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Product Name<span class="required">*</span></label>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Product Name" name="name">
          </div>
        </div>
      </div>
  </div>
  	<!--Upload Base Image Start-->
      <div class="row">
      <div class="form-horizontal col-md-12 uploadbase-image">
        <div class="form-group">
          <label class="col-sm-2 control-label">Uplaod Base Images</label>
          <div class="col-sm-10" id="upload_classified_product_image">
            <div class="custom-radio">
            @for($i =0;$i<=4;$i++)
              <label for="baseimage{{$i}}">
              @if($i == 0)
              <input type="radio" name="baseimage" value="{{ $i }}"  checked="" id="baseimage{{$i}}">
              @else
              <input type="radio" name="baseimage" value="{{ $i }}" id="baseimage{{$i}}">
              @endif
              <span></span>
              <span class="basebox"> <a class="close" href="#" title="Delete" onclick="resetimage({{$i}})">Delete</a> <img id="upload_image{{$i}}" src="{{URL('/assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </span>
              </label>
            @endfor
             
            </div>
            <span class="basebox addimage">
            <!-- <input type="file" name="files[]" multiple> -->
            <input type="file" name="product_files[]"  multiple id="imgClassifiedProduct"/>
            <small class="value">Add Image</small></span> <span class="marktext">(Please select the radio button below the image to mark as default).</span> </div>
        </div>

       
        <div class="form-group">
          <label for="uploadvideo" class="col-sm-2 control-label">Upload Video</label>
          <div class="col-md-6 col-sm-10">
            <div class="file" id="upload_classified_product_video">
              <input type="file" name="uploadvideo" id="input-uploadvideo">
              <span class="value"></span> <span class="btn btn-primary">Browse</span> </div>
            <span class="small-semibold">You can upload a video up to 25 megabytes (MB) in size or provide a link of video</span>
          </div>

          <!-- <div class="col-md-4 col-sm-offset-2 col-sm-10">
          	<div class="videoimage-box">
              	<img src="bootstrap/img/category-218-174-1.jpg" alt="Category">
                  <div class="overly"></div>
              </div>
          </div> -->

        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <label class="sr-only" for="urllink"></label>
            <input type="text" name="video_link" id="video_link" class="form-control" placeholder="https://www.youtube.com/watch?v=cx83">
          </div>
        </div>
      </div>
    </div>
      <!--Upload Base Image End-->
      
      @include('front.sell.product.partial.category_select')
      
    	
      
      <!-- <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Condition<span class="required">*</span></label>
          <div class="selectbox col-md-4">
            <select class="selectpicker">
              <option selected="" value="New">New</option>
              <option value="Unboxed">Unboxed</option>
            </select>
          </div>
        </div>
      </div> -->

      <div class="row">
            <div class="form-horizontal col-md-12">
                <div class="form-group">
                    <label for="product_condition_id" class="control-label col-md-2">Product condition</label>
                    <div class="selectbox col-md-4" id="productCondition">
                        {!! Form::select('product_condition_id', $productConditions, null, ['class'=>'form-control selectpicker', 'id' => 'product_condition_id']) !!}
                        <!--style="display:block !important;"-->
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    @include('front.sell.product.partial.description')
    
    <div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group mrg-bottom">
          <label class="control-label col-md-2 padd-topnone">Product Origin<span class="required">*</span></label>
          <div class="col-md-10"> <span class="mrg-right10">Same as seller's mailing address</span>
            <div class="custom-radio">
              <label for="originyes">
                <input id="originyes" type="radio" value="Yes" name="product_origin" checked>
                <span></span>Yes
              </label>
              <label for="originno">
                <input id="originno" type="radio" value="No" name="product_origin">
                <span></span>No
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    @include('front.sell.product.modal.create_address')

    <div class="row">
    <div class="form-horizontal col-md-12">
      <div class="form-group flex-wrap">
        <label class="control-label col-md-2">Preview Scheduled</label>
        <div class="col-md-10">
          <div class="scheduled-field addjquery clearfix">
             <div class="scheduled-outer">
              <div class="col-md-4">
                <label>Select Days</label>
                  <div class="outer-field dateouter">
                    <input type="text" class="form-control datepicker-ui" mindate="0" name="available_date[]">
                  </div>
              </div>
              <div class="col-md-4">
              <label>Specify Time</label>
                <div class="outer-field tofrom-outer">
                  <label>From</label>
                  <input type="text" class="form-control" name="from_time[]">
                </div>
              </div>
              <div class="col-md-4">
              <label class="invisible">Specify Time</label>
                  <div class="outer-field tofrom-outer">
                  <label>To</label>
                <input type="text" class="form-control" name="to_time[]">
              </div>
              </div>
              </div>
              <div class="col-xs-12 classified_link">
              <a href="#" class="link">+ Add More</a>
             </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    <!--Upload Contract Start for set a product preview functionality-->
    @if($product_type=='preview')
      <div class="row">
      <div class="form-horizontal col-md-12 uploadbase-image">
        <div class="form-group">
          <label class="col-sm-2 control-label">Upload Contract</label>
          <div class="col-sm-10" id="upload_classified_product_image">
            <div class="custom-radio">
                @for($i =0;$i<=4;$i++)
                    <label for="baseimage{{$i}}">
                        @if($i == 0)
                            <input type="radio" name="baseimage" value="{{ $i }}"  checked="" id="baseimage{{$i}}">
                        @else
                            <input type="radio" name="baseimage" value="{{ $i }}" id="baseimage{{$i}}">
                        @endif
                    <span></span>
                    <span class="basebox"> <a class="close" href="#" title="Delete" onclick="resetimage({{$i}})">Delete</a> <img id="upload_image{{$i}}" src="{{URL('/assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </span>
                    </label>
                @endfor             
            </div>
            <span class="basebox addimage">
            <!-- <input type="file" name="files[]" multiple> -->
            <input type="file" name="product_files[]"  multiple id="imgClassifiedProduct"/>
            <small class="value">Add Image</small></span> <span class="marktext">(Please select the radio button below the image to mark as default).</span> </div>
        </div>

       
        
        
      </div>
    </div>
    @endif
      <!--Upload Contract End-->

    @if(!empty($classifiedRelatedProduct))

    <div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group flex-wrap">
          <label class="control-label col-md-2">Related products</label>
           <div class="col-md-4">
            <div class="attrfield">
              <div class="vertical custom-checkbox">
                @foreach($classifiedRelatedProduct as $key => $value)
                  <label>
                    <input type="checkbox" value="{{ $value['id'] }}" name="related_product[]">
                      <span></span>{{ $value['name'] }}
                    </label>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @endif

   	<div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Meta Tag</label>
          <div class="col-md-10">
            <input type="text" class="form-control" placeholder="Meta Tag" name="meta_tag">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Meta Keyword</label>
          <div class="col-md-10">
            <textarea type="text" class="form-control" rows="3" placeholder="Meta Keyword" name="meta_keyword"></textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Meta Description </label>
          <div class="col-md-10">
            <textarea type="text" class="form-control" rows="3" placeholder="Meta Description" name="meta_description"></textarea>
          </div>
        </div>
        <div class="form-group">
        	<div class="col-md-10 col-md-offset-2">
          	<div class="custom-checkbox">
              	<label for="checkbox-terms_conditions">
                  <input type="checkbox" id="checkbox-terms_conditions" name="terms_conditions"><span></span>Accept Terms and Conditions
                </label>
              </div>
          </div>
        </div>
      </div>
    </div>
    <div style="display:none" id="classified_hidden_input"></div>
    	<!--Meta Information End-->
      <hr>
      <div class="form-btnblock text-right nomargin">
        <input type="hidden" value="" name="submit_type" /> 
      	<!-- <a href="#" class="btn btn-lightgray" title="Save as Draft">save as draft</a>  -->
        <input type="button" title="Save as draft" class="btn btn-lightgray productSubmitCls" value="Save as draft" />
    		<input type="submit" title="Preview & Publish" class="btn btn-primary productSubmitCls" value="Preview & Publish">
      </div>
  </div>      
  <!--Preview Page End--> 
</div>
</div>
<!--Rightside End --> 
@push('scripts')
<script type="text/javascript">

function readURL(input) {
    var imagecount = 4;
    if (input.files)
    {
      $( input.files ).each(function( ival ) {
        //console.log(input.files[ival]);
        var reader = new FileReader();  
        reader.onload = function (e) {
          $('#upload_image'+ival).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[ival]);

        $('#hidden_input_'+ival).remove();

        if(ival > imagecount)
        {
          alert('Can\'t upload more than 5 image');
          return false;
        }

      });
    }
}
$("#imgClassifiedProduct").change(function(){
    readURL(this);
});

function resetimage(ival)
{
  $('#upload_image'+ival).attr('src', '{{URL("/assets/front/img/xs-thumb.jpg")}}');
  $('#classified_hidden_input').append('<input type="hidden" name="remove_image_id[]" id="hidden_input_'+ival+'" value="'+ival+'" />');
}

</script>
@endpush
