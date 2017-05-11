<!--Rightside Start -->
<div class="rightcol-bg clearfix">
<div class="equal-column">
  <h4>Add New Classified Product (Set a Preview)</h4>
  <div class="sell-addproduct">
 		<div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Listing Status</label>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="{{ $classifiedDetails['status'] }}" disabled="disabled">
          </div>
        </div>
      </div>
  </div>
  	<hr>
      <h5 class="blacktitle">General Details</h5>
      <div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Product Name<span class="required">*</span></label>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Product Name" name="name" value="{{ $classifiedDetails['name'] }}">
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
           @php ($i = 0)
           @php ($j = 5 -count($classifiedDetails->Files))

            @foreach($classifiedDetails->Files as $key => $file)
              @if($file['file_type'] == 'image')
                
                <label for="baseimage{{$file['id']}}">
                @if($classifiedDetails['default_image_id'] == $file['id'])
                <input type="radio" name="baseimage" value="{{ $file['id'] }}"  checked="" id="baseimage{{$file['id']}}">
                @else
                <input type="radio" name="baseimage" value="{{ $file['id'] }}" id="baseimage{{$file['id']}}">
                @endif
                <span></span>
                <span class="basebox"> <a class="close classified_image_close" image_id="{{encrypt($file['id'])}}" href="#" title="Delete">Delete</a> 
                <img id="remove_upload_image{{$file['id']}}" src="{{getImageFullPath($file['path'],'classified_products/'.$classifiedDetails['user_id'],'thumbnail')}}" width="46" height="46" alt=""> </span>
                </label>
                @php
                $i++;
                @endphp
              
              @elseif($file['file_type'] == 'video')
              
              @php $video_path = getImageFullPath($file['path'],'classified_products/'.$classifiedDetails['user_id'],'video');
              $video_id        = $file['id'];
              @endphp

              @endif
            
            @endforeach
            
            @if($i < 5)
               @php ($i = 5 - $i)
              @for($j = 0;$j < $i;$j++)
                <label for="baseimage{{$j}}">
                  <input type="radio" name="baseimage" value="new_{{ $j }}" id="baseimage{{$j}}">
                  <span></span>
                  <span class="basebox"> <a class="close" onclick="resetimage({{$j}})"  href="#" title="Delete" >Delete</a>
                  <img id="upload_image{{$j}}" image_count="{{$j}}" src="{{URL('/assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""></span>
                </label>
              @endfor
            @endif
            
            </div>
            <span class="basebox addimage">
            <!-- <input type="file" name="files[]" multiple> -->
            <input type="file" name="update_product_files[]"  multiple id="imgClassifiedProduct"/>
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
        </div>
        
        @if(isset($video_path))
        <div class="form-group" class="clear" id="video_div">
          <!-- <video width="220" height="140" controls autoplay>
            <source src="{{ $video_path }}" type="video/mp4">
            Sorry, your browser doesn't support the video element.
          </video> -->
          

<video width="220" controls>
  <source src="{{$video_path}}" type="video/mp4">
  
  Your browser does not support HTML5 video.
</video>
       <!--    <embed src="{{ $video_path }}"></embed> -->
        </div>
        <a href="javascript:void(0)" class="link" id="remove_classified_video" remove_id = "{{ encrypt($video_id) }}"> Remove video</a>
        <input type="hidden" name="upload_video_id" value="{{ encrypt($video_id) }}"/>
        @endif

        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-4">
            <label class="sr-only" for="urllink"></label>
            <input type="text" name="video_link" id="video_link" value="{{ $classifiedDetails['video_link'] }}" class="form-control" placeholder="https://www.youtube.com/watch?v=cx83">
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
                @if($classifiedDetails['product_origin'] == "Yes")
                  <input id="originyes" type="radio" value="Yes" name="product_origin" checked="checked">
                @else
                  <input id="originyes" type="radio" value="Yes" name="product_origin">
                @endif
                <span></span>Yes
              </label>
              <label for="originno">
                @if($classifiedDetails['product_origin'] == "No")
                  <input id="originno" type="radio" value="No" name="product_origin" checked="checked">
                @else
                  <input id="originno" type="radio" value="No" name="product_origin">
                @endif
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
             @foreach($classifiedDetails->classifiedDayTime as  $key => $time)
                @if($key  == 0)  
                  <div class="scheduled-outer">
                    <div class="col-md-4">
                      <label>Select Days</label>
                        <div class="outer-field dateouter">
                          <input type="text" class="form-control datepicker-ui" mindate="0" name="available_date[]" value="{{ date('d-M-Y',strtotime($time['available_date'])) }}" >
                        </div>
                    </div>
                    <div class="col-md-4">
                    <label>Specify Time</label>
                      <div class="outer-field tofrom-outer">
                        <label>From</label>
                        <input type="text" class="form-control" name="from_time[]" value="{{ $time['from_time'] }}">
                      </div>
                    </div>
                    <div class="col-md-4">
                    <label class="invisible">Specify Time</label>
                        <div class="outer-field tofrom-outer">
                        <label>To</label>
                      <input type="text" class="form-control" name="to_time[]" value="{{ $time['to_time'] }}">
                    </div>
                    </div>
                  </div>
                @else

                <div class="scheduled-outer">
                  <div class="col-md-4">
                    <div class="outer-field dateouter">
                      <input type="text" class="form-control datepicker-ui" mindate="0" name="available_date[]" value="{{ date('d-M-Y',strtotime($time['available_date'])) }}">
                      <button type="button" class="ui-datepicker-trigger">...</button>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="outer-field tofrom-outer">
                      <label>From</label><input type="text" class="form-control" name="from_time[]" value="{{ $time['from_time'] }}">
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="outer-field tofrom-outer"><label>To</label>
                      <input type="text" class="form-control" name="to_time[]" value="{{ $time['to_time'] }}">
                    </div>
                  </div><a href="#" class="remove_field">Remove</a>
                </div>

              @endif

            @endforeach  
              <div class="col-xs-12 classified_link">
              <a href="#" class="link">+ Add More</a>
             </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if(!empty($classifiedRelatedAllProduct))

    <div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group flex-wrap">
          <label class="control-label col-md-2">Related products</label>
           <div class="col-md-4">
            <div class="attrfield">
              <div class="vertical custom-checkbox">
                @foreach($classifiedRelatedAllProduct as $key => $value)
                  <label>
                    @if(in_array($value['id'],$classifiedRelatedProduct))
                    <input type="checkbox" value="{{ $value['id'] }}" checked="checked" name="related_product[]">
                    @else
                    <input type="checkbox" value="{{ $value['id'] }}" name="related_product[]">
                    @endif
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
            <input type="text" class="form-control" placeholder="Meta Tag" name="meta_tag" value="{{ $classifiedDetails['meta_tag'] }}">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Meta Keyword</label>
          <div class="col-md-10">
            <textarea type="text" class="form-control" rows="3" placeholder="Meta Keyword" name="meta_keyword"> {{ $classifiedDetails['meta_keyword'] }} </textarea>
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Meta Description </label>
          <div class="col-md-10">
            <textarea type="text" class="form-control" rows="3" placeholder="Meta Description" name="meta_description"> {{ $classifiedDetails['meta_description'] }} </textarea>
          </div>
        </div>
        <div class="form-group" style="display:none">
              <div class="col-md-10 col-md-offset-2">
                <div class="custom-checkbox">
                    <label for="checkbox-terms_conditions">
                      <input type="checkbox" id="checkbox-terms_conditions" checked="checked" name="terms_conditions"><span></span>Accept Terms and Conditions
                    </label>
                  </div>
              </div>
            </div>
          </div>
        </div>
    	<!--Meta Information End-->
      <div style="display:none" id="classified_hidden_input"></div>
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
var imagecount = {{ $j }};
var count_file = 1;
function readURL(input) {
    if (input.files)
    {
      $( input.files ).each(function( ival ) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#upload_image'+ival).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[ival]);
        $('#hidden_input_'+ival).remove();
        if(ival > imagecount || imagecount == 0 || count_file > imagecount)
        {
          imagecount = 0; 
          toastr.error('Can\'t upload more than 5 image');
          return false;
        }

        count_file++;
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
  count_file--;
}

$('.classified_image_close').on('click',function(){

  var image_id = $(this).attr('image_id');
  var user_id  = "{{ encrypt($classifiedDetails['user_id']) }}";
  var _this    = $(this);
  
  if (confirm('Are you sure want to remove this image ?') == true) {
    $.ajax({
          url: '{{route("removeImage")}}',
          type: 'POST',
          dataType: 'json',
          data: {method: '_POST', image_id:image_id,user_id:user_id, submit: true},
          success: function (r) {
              if(r.status == 'success')
              {
                /*_this.attr('src', '{{URL("/assets/front/img/xs-thumb.jpg")}}');
                _this.attr('class', 'classified_no_image');*/
                location.reload();
              }else{
                toastr.error('There is some error, Please try again later');
              }  
          },
          error: function (data) {
          
          }
      });
  }
});


$('#remove_classified_video').on('click',function(){

  var video_id = $(this).attr('remove_id');
  var user_id  = "{{ encrypt($classifiedDetails['user_id']) }}";
  var _this    = $(this);
  
  if (confirm('Are you sure want to remove this video ?') == true) {
    $.ajax({
          url: '{{route("removeVideo")}}',
          type: 'POST',
          dataType: 'json',
          data: {method: '_POST', video_id:video_id,user_id:user_id, submit: true},
          success: function (r) {
              if(r.status == 'success')
              {
                location.reload();
              }else{
                alert('There is some error, Please try again later');
              }  
          },
            error: function (data) {
          
          }
      });
  }
});


</script>
@endpush
