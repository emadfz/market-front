@extends('front.buy.layout')

@section('pageContent')
   
<style type="text/css">
  


</style>

<!--Rightside Start -->
{!! Form::open(['url' => route('storeAdd'),'class'=>'ajax','id'=>'post_add_form','enctype'=>'multipart/form-data']) !!}
<div class="rightcol-bg clearfix  buy-addads">
  <div class="equal-column">
    <h4>Post an Ad</h4>
    <h5 class="blacktitle">Advertise (Please specify your details)</h5>
    <hr class="mrg-topnone">
    <div class="row">
      <div class="form-horizontal col-md-12">
        <div class="form-group">
          <label class="control-label col-md-2">Advertisement Name</label>
          <div class="col-md-4"> 
            <input type="text" class="form-control" name="advertisement_name">
          </div>
        </div>
        <div class="form-group mrg-bott10">
          <label class="control-label col-md-2 padd-topnone">Select Type</label>
          <div class="col-md-10">
            <div class="custom-radio">
              <label for="adbanner">
                <input id="adbanner" type="radio" value="Banner" name="type" checked="">
                <span></span>Banner Ad</label>
              <label for="adbox">
                <input id="adbox" type="radio" value="Main_Box" name="type">
                <span></span>Main Ad</label>
            </div>
          </div>
        </div>
        <div class="form-group mrg-bott10">
          <label class="control-label col-md-2 padd-topnone">Select Location</label>
          <div class="col-md-10">
            <div class="custom-radio">
              <label for="adshome">
                <input id="adshome" type="radio" value="adhomepage" name="location" checked="">
                <span></span>Home Page</label>
              <label for="adscat">
                <input id="adscat" type="radio" value="adcategory" name="location">
                <span></span>Category Page</label>
              <label for="adssubcat">
                <input id="adssubcat" type="radio" value="admingle" name="location">
                <span></span>Mingle</label>
            </div>
          </div>
        </div>
        <!--Radio click show/hide-->
        <!-- <div class="form-group">
        		<div class="col-md-4 col-md-offset-2">
              	<div class="showhome adsbox" style="display:block">
                  	<div class="adsinner">
                  		<img src="{{url('bootstrap/img/addpreview.jpg')}}" width="254" height="156" alt="Ads">
                    </div>
                  	<p class="infotext">Image Should be 820px*450px</p>
                </div>
			  <div class="showcat adsbox">
                  	<div class="adsinner">
                  		<img src="bootstrap/img/banner-bags.jpg" width="340" height="143" alt="Ads">
                      </div>
                  	<p class="infotext">Image Should be 970px*420px</p>
                  </div>
  			<div class="showsubcat adsbox">
                  	<div class="adsinner">
                  		<img src="bootstrap/img/banner-fashion.jpg" alt="Ads" width="340" height="143">
                      </div>
                  	<p class="infotext">Image Should be 970px*420px</p>
                  </div>
              </div>
        </div> -->
        
        <div style="display: none" id="post_add_category">
          @include('front.sell.product.partial.category_select')
        </div>

        <div class="form-group mrg-bott10">
          <label class="control-label col-md-2">Select Start Date</label>
          <div class="col-md-4">
            <div class="outer-field dateouter">
              <input type="text" class="form-control datepicker-ui" mindate="0" name="start_date">
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-6 selectbox col-md-offset-2"> Available days: 25 based on the start date you have selected, Please specify for how much days you want the advertisement </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Select No of days</label>
          <div class="col-md-4 selectbox">
            <select class="selectpicker" data-width="100px" name="no_of_days">
              <option>7</option>
              <option>15</option>
              <option>30</option>
            </select>
          </div>
        </div>
        <div class="form-group">
        		<label class="control-label col-md-2">Upload Image</label>
              <div class="col-md-4">
             	<span class="basebox addimage">
          		<input type="file" name="ad_image" id="imgInp">
          		<small class="value">Add Image</small></span>
              </div>
        </div>
        <div class="form-group">
          <label class="control-label col-md-2">Link</label>
          <div class="col-md-4">
            <input type="text" class="form-control" placeholder="Devanche.com/#p=product_details_page" name="advertisement_link">
          </div>
        </div>
        <hr>
        <div class="form-btnblock text-right nomargin"> 
    	   <a href="{{ route('advertisements') }}"   class="btn btn-lightgray" title="Close">Close</a>
      	<a href="#large" id="id_preview" data-toggle="modal" class="btn btn-lightgray" title="Show Preview">Show Preview</a>
      	<input type="submit" title="Submit & Pay" class="btn btn-primary" value="Submit & pay">
   	 </div>
      </div>
    </div>
    
    
  </div>
</div>
    {!! Form::close() !!}

{!! Form::close() !!}

<div class="modal" id="large" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-inner clearfix">
                <a href="#" class="close" data-dismiss="modal" aria-hidden="true"></a>
                <h6 class="modal-title">{!!trans("message.preview")!!}</h6>
           
          
              <div class="clearfix">
                  <div class="slides-outer">
                    <a href="#" id="main_box_a"><img src="{{ URL("/assets/front/img/no-image-main.png" ) }}" id="main_box" alt="No Image" class="advrt_image " width="820" height="450"></a>
                  </div>
                <div class="small-banner">
                    <a href="#"><img src="{{ URL("/assets/front/img/small-noimage.png" ) }}"  alt="No Image" class="advrt_image" height="143" width="340"></a>
                    <a href="#" id="banner_a"><img src="{{ URL("/assets/front/img/small-noimage.png" ) }}" id="banner" alt="No Image" class="advrt_image" height="143" width="340"></a>
                    <a href="#"><img src="{{ URL("/assets/front/img/small-noimage.png" ) }}" alt="No Image" class="advrt_image" height="143" width="340"></a>
                </div>
              </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Purchased Ads'=>'']])
@endpush  

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $(".product-nav li .submenu li.active").each(function () {
            $(this).parent().addClass("openouter").parent("li").addClass("active");
        });
        $(".product-nav li").on("click", function () {
            $('.product-nav li > ul').not($(this).children("ul").toggle()).hide();
        })
		$('.buy-addads input[type="radio"]').click(function(){
        if($(this).attr("value")=="adhomepage"){
            $(".adsbox").not(".showhome").hide();
            $(".showhome").show();
            $('#post_add_category').hide();
        }
        if($(this).attr("value")=="adcategory"){
            $(".adsbox").not(".showcat").hide();
            $(".showcat").show();
            $('#post_add_category').show();
        }
        if($(this).attr("value")=="admingle"){
            $(".adsbox").not(".showsubcat").hide();
            $(".showsubcat").show();
            $('#post_add_category').hide();
        }
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();            
            reader.onload = function (e) {
                $('.advrt_image').attr('src', '{{ URL("/assets/front/img/no-image-main.png" ) }}');
                var type = $('input:radio[name=type]:checked').val();                
                if(type == 'Main_Box')
                {
                    $('#main_box').attr('src', e.target.result);
                    $('#main_box_a').attr('href', $( "#advr_url" ).val());
                }
                else if(type == 'Banner')
                {
                  $('#banner').attr('src', e.target.result);
                  $('#banner_a').attr('href', $( "#advr_url" ).val());
                }

                $('.addimage').append('<img width="100" height="100" src="'+e.target.result+'">');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#imgInp").change(function(){
        readURL(this);
    });

    $('#adbanner,#adbox').change(function(){
        $("#imgInp").trigger('change');
    });

	});
</script>
<script src="{{ asset('assets/front/js/owl.js') }}" type="text/javascript"></script>
@endpush