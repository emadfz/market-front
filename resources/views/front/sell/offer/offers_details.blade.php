@extends('front.sell.layout')

@section('pageContent')
<div class="rightcol-bg">
  <h4>Make an offer Activity Details</h4>
  <!--Sell Bids Details Table Start-->
  <table class="table table-bordered selloffer-detail mobile-table">
      <tbody>
        <tr>
          <td class="col1">product ID</td>
          <td class="col2" data-title="Product ID">{{ $offers_details['id'] }}</td>
          <td class="col1">product name</td>
          <td class="col2" data-title="Product Name">{{ $offers_details['name'] }}</td>
        </tr>
        <tr>
          <td class="col1">status</td>
          <td class="col2" data-title="Status">{{ $offers_details['status'] }}</td>
          <td class="col1">no of bidders</td>
          <td class="col2" data-title="No of bidders">{{ $offers_details->offers->count() }}</td>
        </tr>
        <tr>
          <td class="col1">minimum product price($)</td>
          <td class="col2" data-title="Product Price($)">1000</td>
          <td class="col1">minimum reserved price</td>
          <td class="col2" data-title="Reserved Price($)">{{ $offers_details['min_reserved_price'] }}</td>
        </tr>
      </tbody>
    </table>
   
    <table class="table table-bordered selloffer-statustable mobile-table" id="offerDetailsListing">
      <thead>
        <tr>
          <th class="buycol">Buyer Name</th>
          <th class="col2">Offer Price</th>
          <th class="col3">Counter Offer</th>
          <th class="col4">Offer Status</th>
          <th class="col5">Status</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    
    <div class="modal in" role="dialog" id="offer-modal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
  <!--Sell Bids Details Table End--> 
</div>
<!--Rightside End -->
 @endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Product Offers'=>'']])
@endpush
 
@push('scripts')

<script>
    //Extend Datatable Default Setting
    $.extend(true, $.fn.dataTable.defaults, {
        "scrollX": true,
        "language": {
            "lengthMenu": "Show entries _MENU_",
            "zeroRecords": "No records found",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records found",
            "infoFiltered": "(filtered from _MAX_ total records)"
        },
        order: [[1, 'desc']],
    });

    var oTable = $('#offerDetailsListing').DataTable({
        // dom: "lprtip",
        oLanguage: {sLengthMenu: "<select class='selectpicker'><option value='10'>10</option><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option><option value='-1'>All</option></select>"},
        processing: true,
        serverSide: true,
        /*responsive: true,*/
        ajax: {
            type: 'POST',
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}')
            },
            url: "{{ route('offerDetailsList',[$offers_details['id']] )}}",
        },
        columns: [
            {data: 'buyer_name', name: 'buyer_name', searchable: false,orderable: false,},
            {data: 'offer_price', name: 'offers.offer_price', searchable: false,orderable: false,},
            {data: 'counter_offer', name: 'counter_offer', searchable: false, orderable: false},
            {data: 'buyer_offer_price', name: 'buyer_offer_price', searchable: false,orderable: false,},
            {data: 'offer_status', name: 'offer_status', searchable: false, orderable: false},
        ],
         "initComplete": function(settings, json) {

            $("#counterModel").on('click',function(ev) {            
                ev.preventDefault();
                var target  = $(this).attr("href");
                var modalId = $(this).data("target");
                
                
                /*$("#shippingCalculator .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');*/
                // load the url and show modal on success
                $(modalId+" .modal-body").load(target, function() { 
                    $(modalId).modal("show"); 
                    //$.unblockUI();
                });
            });    
        }
    });

    /* Remove Search Box*/
    $('.dataTables_filter input').remove();
    $('#offerDetailsListing_filter').remove();
    
</script>
@endpush 
