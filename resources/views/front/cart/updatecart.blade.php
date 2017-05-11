@extends('front.layouts.app')

@section('content')
 <!-- Start container here -->
  <section class="content">
    <div class="container"> 
      <!--breadcrumb Start-->
      <div class="row">
        <div class="col-md-12">
          <ul class="breadcrumb">
            <li><a href="index.html">Home</a></li>
            <li class="active">Review Orders</li>
          </ul>
        </div>
      </div>
      <!--breadcrumb Start-->
      <h2>Review Orders</h2>
      <!-- Nav tabs Start-->
      <ul class="myactivity-tab clearfix">
        <li><a href="{{route('cart')}}" title="Review Order">Shopping Cart</a></li>
        <li class="active"><a href="{{route('checkout')}}" title="Incomplete Orders">Review Order</a></li>
        <li class=""><a href="{{route('payment')}}">Payment</a></li>
      </ul>
      <!-- Nav tabs End-->
      <div class="shoppingcart">
        @include('front.cart.updatebascket')
      </div>
    </div>
  </section>
  <!-- End container here --> 
  
<!-- Modal Edit Address Start-->

<div class="modal" role="dialog"  id="editaddress1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">            
            <div class="modal-body" >                
            </div>                         
        </div>
    </div>
</div>


<!--<div class="modal" role="dialog"  id="editaddress" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-inner clearfix">
      <a href="#" class="close" data-dismiss="modal">close</a>
      	<ul class="edit-cartaddress clearfix">
        	<li>
            	<div class="col-md-6 left">
                	<div class="custom-radio">
                	<label for="editoption">
                    	<input type="radio" name="editaddress" value="red" id="editoption"><span></span>
                        Ganesh Hulle 123 Victory Drive Delhi,IN 56789
                    </label>
                    </div>
                	<a href="#" title="Edit" class="link">Edit</a>
                </div>
                <div class="col-md-6 right">
                	<div class="vertical custom-radio">
                    	<label for="freeship">
                    		<input type="radio" name="shipoption" value="red" id="freeship" checked><span></span>
                        	Free Shipping
                    	</label>
                        <label for="fedexship" class="bluecolor">
                    		<input type="radio" name="shipoption" value="red" id="fedexship"><span></span>
                        	FedEx 2-day delivery <em>$45.00</em>
                    	</label>
                    </div>
                </div>
            </li>
            <li>
            	<div class="col-md-6 left">
                	<div class="custom-radio">
                	<label for="editoption2">
                    	<input type="radio" name="editaddress" value="red" id="editoption2" checked><span></span>
                        Ankur Dossi 2675 Liberty Avenue Ontario, CA 67890iu
                    </label>
                    </div>
                	<a href="#" title="Edit" class="link">Edit</a>
                </div>
                <div class="col-md-6 right">
                	<div class="vertical custom-radio">
                    	<label for="freeship2" class="bluecolor">
                    		<input type="radio" name="shipoption2" value="red" id="freeship2" checked><span></span>
                        	USPS 14-day delivery<em>$15.00</em>
                    	</label>
                        <label for="fedexship2" class="bluecolor">
                    		<input type="radio" name="shipoption2" value="red" id="fedexship2"><span></span>
                        	FedEx 2-day delivery<em>$45.00</em>
                    	</label>
                    </div>
                </div>
            </li>
        </ul>  
        <div class="clearfix text-right">
            <a href="#addaddressmodal" title="Add New Address" class="link pull-left" data-toggle="modal" data-dismiss="modal">Add New Address</a>
            <input type="submit" title="Done" class="btn btn-primary" value="Done">
        </div>	
        
         
    </div>
    </div>
  </div>
</div>-->
<!--Modal Edit Address Close-->
<!-- Modal Add New Address Start-->
<div class="modal" id="addaddressmodal" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-inner clearfix">
      <a href="#" class="close" data-dismiss="modal">close</a>
      		<h4 class="blacktitle">Add / Edit Shipping Address</h4>
       		<div class="form-horizontal">
            	<div class="form-group">
                	<label for="addmodalname" class="col-sm-4 control-label">Name<span class="required">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="addmodalname" placeholder="XXXX">
                    </div>
                </div>
                <div class="form-group">
                	<label for="addmodaladdress" class="col-sm-4 control-label">Address<span class="required">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="addmodaladdress" placeholder="XXXX">
                    </div>
                </div>
                <div class="form-group">
                	<label for="addmodalcountry" class="col-sm-4 control-label">Country<span class="required">*</span></label>
                    <div class="col-sm-8 selectbox">
                      <select id="addmodalcountry" class="selectpicker">
                        <option selected="" value="All Country">Select Country</option>
                        <option value="India">India</option>
                        <option value="United States">United States</option>
                        <option value="Canada">Canada</option>
                    </select>
                    </div>
                </div>
                <div class="form-group">
                	<label for="addmodalzipcode" class="col-sm-4 control-label">ZIP/ Postal Code<span class="required">*</span></label>
                    <div class="col-sm-8">
                      <input type="text" class="form-control" id="addmodalzipcode" placeholder="XXXXXX">
                    </div>
	            </div>
               
               <div class="form-group">
                <label for="addmodalstate" class="col-sm-4 control-label">State / Province<span class="required">*</span></label>
                <div class="col-sm-8">
                  <div class="selectbox">
                    <select id="addmodalstate" class="selectpicker">
                        <option selected="" value="All State">Select State</option>
                        <option value='AL'>Alabama</option>
						<option value='AK'>Alaska</option>
						<option value='AZ'>Arizona</option>
						<option value='AR'>Arkansas</option>
						<option value='CA'>California</option>
                    </select>
                	</div>
                </div>
              </div>
               <div class="form-group">
                <label for="addmodalcity" class="col-sm-4 control-label">City<span class="required">*</span></label>
                <div class="col-sm-8">
                  <div class="selectbox">
                    <select id="addmodalcity" class="selectpicker">
                        <option selected="" value="All City">Select City</option>
                        <option value=" Absecon"> Absecon</option>
                        <option value="Adelanto">Adelanto</option>
                        <option value="Albany">Albany</option>
                        <option value="AlumRock">Alum Rock</option>
                    </select>
                	</div>
                </div>
              </div>
              
                
    	    </div>
            <div class="clearfix text-right">
                <a href="#editaddress" title="Cancel" class="cancel-link" data-toggle="modal" data-dismiss="modal">Cancel</a>
            	<input type="submit" title="Submit" class="btn btn-primary" value="submit">
            </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Add New Address Close-->
@endsection
@push('scripts')
<script>
  $('.promo_code').keypress(function (e) {
      if (e.which == 13) {
        $('#'+$(this).data('apply-id')).trigger('click');          
      }
  });
/*   $.ajax({
            url: window.location.href+'cart',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
            },
            error: function (data) {
            }
        });*/
function remove_bascket() {    
     var allVals = [];
     var checkedValues = $('input[name="c_b"]:checked').map(function() {
    allVals.push($(this).val());
    }).get();
    if(confirm("Are you sure you want to delete this item?")){
    $.ajax({
            url: '{{route("remove_bascket_shopping")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_ids: allVals, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);                
            },
            error: function (data) {
            }
        });
           //location.href="{{URL::to('cart')}}";
    }       
  }
function apply_coupon(){
 
}  


function check_promo(promo , user_id)
{
    $.ajax({
            url: 'ajax_promo/'+promo+'/'+user_id,
            type: 'GET',
            dataType: 'json',
            data: {method: '_GET', promo_code: promo,user_id :user_id },
            success: function (r) {
                  if (r.status == 'success')
                  {
                    toastr.success(r.message + 'you have a discount of ' + r.discount + ' ' + r.discount_type) ;

                    document.getElementById('td1').innerHTML = "$90";
                  }
                  else
                  {
                    toastr.error(r.message);
                  }
                  
            },
            error: function (data)
            {
                toastr.error(data.message);
            }
        });
}




$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});
function updateqtycart(id,qty){
        $.ajax({
            url: '{{route("updateqtyshoppingcart")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id,qty :qty, submit: true},
            success: function (r) {
                $(".shoppingcart").html(r['html']);
            },
            error: function (data) {
            }
        });
    }

    $('#add_cart_value').click(
        function()
        {
            $('#cart-total').html(parseFloat($('#cart-total').html()) + parseFloat($('#donation_amount').val()));

            $("#cart_total").prepend("<div style='background-color:rgba(38, 198, 218, 0.2)' > Donation for  " +$('#vendor').val()+" -> $"+   $('#donation_amount').val() +"<a onclick='javascript:$(this).closest('div').remove();' style='color: red'> x </a> </div>");

                $("#donation_amount").val("");
        }
    );

</script>

@endpush('scripts')
