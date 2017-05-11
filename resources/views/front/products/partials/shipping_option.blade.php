<div style="float: right">Pincode <input type="text" name="pincode" id="pincode" /></div><br/>
<div class="buyway-inner shippertype">    
    <h5>Shipping         
        <a data-target="#shippingCalculator" class="ajaxModal" href="{{URL('/shippingRate/99205/382481')}}" data-placement="middle"  title="Shipping Calculator">Shipping Calculator</a>        
    </h5>    
    <div class="vertical custom-radio">
        <label for="sfedex">
            <input id="sfedex" type="radio" name="shipping" checked><span></span>
            <small class="shipper-bg"><img src="{{asset('assets/front/img/fedex.png')}}" alt="Fedex" width="48" height="16"></small>
            <p class="small-semibold">Lorem Ipsum is simply dummy text of the printing and typeset has been
                <small>Expected Delivery 15 Days</small></p>
        </label>
        <label for="sdhl">
            <input id="sdhl" type="radio" name="shipping" ><span></span>
            <small class="shipper-bg"><img src="{{asset('assets/front/img/dhl.png')}}" alt="Fedex" width="48" height="16"></small>
            <p class="small-semibold">Lorem Ipsum is simply dummy text of the printing and typeset has been
                <small>Expected Delivery 5 Days</small></p>
        </label>
        <label for="sups">
            <input id="sups" type="radio" name="shipping" ><span></span>
            <small class="shipper-bg"><img src="{{asset('assets/front/img/ups.png')}}" alt="Fedex" width="48" height="16"></small>
            <p class="small-semibold">Lorem Ipsum is simply dummy text of the printing and typeset has been
                <small>Expected Delivery 3 Days</small></p>
        </label> 
    </div>
</div>


<div class="modal" role="dialog"  id="shippingCalculator" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{trans("form.shipping_calculator.title")}}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body" ></div>
             <div class="modal-footer">
                 <a href="#" class="close" data-dismiss="modal"></a>                
            </div>
            
        </div>
    </div>
</div>
@push('scripts')
<script>
var frompincode='99205';
$('#pincode').keyup(function(){
    $('.ajaxModal').attr('href',url+'/shippingRate/'+frompincode+'/'+$(this).val());
});    
</script>
@endpush



