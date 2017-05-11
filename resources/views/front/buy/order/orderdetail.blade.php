@extends('front.buy.layout')

@section('pageContent')

<div class="rightcol-bg clearfix  buy-orderinfo" id="pdf" >
    <div class="equal-column" id="printableArea">
        <h4>Order Information</h4>



        <ul class="overview-classified clearfix no-print">
            <li>My Orders</li>  
          {{--   <li class="small" id="bypassme"><a href="#">View Detail</a></li> 
            <li class="  small no-print  active"  id="bypassme"> View Incoive</li>  --}}
        </ul>
        <div class="advance-filter">
            <div class="pull-right">
                <ul class="invoice-icon clearfix">
                    <li class="prints"><a  class='no-print' href="#" onclick="printinvoice();" id="bypassme" title="Print"><span></span>Print</a></li>
                    <li class="downloads"><a  class='no-print' href="javascript:void(0);" id="create_pdf" title="Email"><span></span>Download</a></li>
                </ul>
            </div>
            <ul class="overview-order clearfix">
                <li>Orders#:<span>{{$order->id}}</span></li>  
                <li>Order status:<span>{{$order->order_status}}</span>


                </li> 
            </ul>

        </div>

        <div class="table-responsive noborder">
            <table class="table without-border"   style="width:auto!impoertant;">
                <tr>
                    <td class="col1">Order Date:</td>
                    <td class="col2">{{$order->created_at}} 
                    </td>
                    <td class="col1">Payment Method:</td>
                    {{-- @foreach($order_payment as $order_payment) --}}

                    <td class="col2">{{$order_payment->payment_method}} </td>
                    {{-- @endforeach --}}
                </tr>
                <tr>
                    <td class="col1">Billing Address:</td>
                    <td class="col2">
                        <table style="width:auto!impoertant;">
                            <tr><td>{{$order_billing->billing_address_1}}</td></tr>
                            <tr><td>{{$order_billing->billing_address_2}}</td></tr>
                            <tr><td>{{$order_billing->city}}</td></tr>
                            <tr><td>{{$order_billing->state}}</td></tr>
                            <tr><td>T: {{$order_billing->phone_number}}</td></tr>
                        </table>
                    </td>

                </tr>
                <tr>
                    <td class="col1">Shipping Method:</td>
                    <td colspan="3">United Parcel Service- 3 day Select</td>
                </tr>
            </table>

           
        </div>
        <hr class="mrg-topnone">
        <h5 class="blacktitle">Item Ordered</h5>

        <!--Buy order history Table Start-->
        <div class="table-responsive">

            <table class="table table-bordered itemorder-table mobile-table"  id="table1" style="width:auto;">
                <thead>
                    <tr>
                        <th class="col1">Product</th>
                        <th class="col2">Manufacturer</th>{{-- was SKU --}}
                        <th class="col3">price</th>
                        <th class="col3">shipping address</th> 
                        <th class="col4">QTY</th>
                        <th class="col5">sub total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php /* $total = 0;?>
                    @foreach($order_shipment as $single_order)




                    <?php 
                   // $product = $single_order->product($single_order->product_id) ;

                    ?>

                    <tr>
                        <td class="col1" data-title="Bidder">{{$product->name}}</td>
                        <td class="col2" data-title="SKU">{{$product->manufacturer}}</td>
                        <td class="col3" data-title="Price">${{$single_order->product_price}}</td> 
                        <td class="col3" data-title="Price">{{$single_order->address}}</td>
                        <td class="col4" data-title="QTY">{{$single_order->quantity}}</td>
                        <td class="col5" data-title="Sub Total">${{$single_order->total_price}}</td>
                    </tr>
                    <?php $total += $single_order->total_price ; ?>
                    @endforeach */ ?>
                </tbody>
            </table>

        </div>
        <div class="itemorder-total text-right">
            <div>Sub Total:<span><?php /*${{$total}}*/ ?></span></div>
            <div>Grand Total:<span><?php /*${{$total}}*/ ?></span></div> 
        </div>
        <hr>
        <div class="clearfix text-right">
            <a href="#" class="btn btn-link no-print" id="bypassme" title="Back To Order"><span class="backarrow"></span>Back To Order</a>
        </div>
        <!--Sell manage order Table End--> 

        <!--Buy order history Table End--> 
    </div>
</div>
<script type="text/javascript" src="//cdn.rawgit.com/MrRio/jsPDF/master/dist/jspdf.min.js">
    </script>
<script>
$(document).ready(function(){
    	var form = $('#pdf'),
	//debuggar;
	cache_width = form.width(),
	a4 = [595.28, 841.89]; // for a4 size paper width and height
	
	$('#create_pdf').on('click', function() {
		//debugger;
                alert("here");
	$('body').scrollTop(0);
	createPDF();
	});
	//create pdf
	function createPDF() {
		getCanvas().then(function(canvas) {
		var
		img = canvas.toDataURL("image/png"),
		doc = new jsPDF({
		unit: 'px',
		format: 'a4'
		});
		doc.addImage(img, 'JPEG', 20, 20);
		doc.save('invoice.pdf');
		form.width(cache_width);
		});
	}
	
	// create canvas object
	function getCanvas() {
		form.width((a4[0] * 1.33333) - 80).css('max-width', 'none');
		return html2canvas(form, {
		imageTimeout: 2000,
		removeContainer: true
		});
	}

});
</script>
<script type="text/javascript">
function printinvoice() {    
		var printContents = document.getElementById('pdf').innerHTML;
		var originalContents = document.body.innerHTML;
		 document.body.innerHTML = printContents;
		 window.print();
		 document.body.innerHTML = originalContents;
    }
</script>
<?php /*
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>

<div id="editor"></div>
<script type="text/javascript">
    function demoFromHTML() {
        var pdf = new jsPDF('p', 'pt', 'letter');

        source = $('#pdf')[0];


        specialElementHandlers = {

            '#bypassme': function(element, renderer) {

                return true
            }
        };
        margins = {
            top: 80,
            bottom: 60,
            left: 40,
            width: 522
        };

        pdf. (
        source, // HTML string or DOM elem ref.
        margins.left, // x coord
        margins.top, {// y coord
        'width': margins.width, // max width of content on PDF
        'elementHandlers': specialElementHandlers
        },
        function(dispose) {

            d = new Date()
            df = d.getMonth()+'-'+d.getDate()+'-'+d.getYear()+' '+(d.getHours()+1)+'_'+d.getMinutes()
            document.execCommand('SaveAs','1','myfile '+df+'.htm')
            pdf.save('Inspree_invoice'+df+'.pdf');
        }
        , margins);
            }
</script>    

*/ ?>
@endsection
@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Buy'=>'' , 'Order Invoice'=>'']])
@endpush

<script type="text/javascript">
   

    function printDiv(divName) {

        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }



</script>
