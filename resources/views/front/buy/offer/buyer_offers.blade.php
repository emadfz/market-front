@extends('front.buy.layout')

@section('pageContent')

  <div class="rightcol-bg clearfix  buy-offer">
    <div class="equal-column">
    <h4>Placed Offers</h4>
    <!--Advance Filter Start-->
    <div class="advance-filter clearfix nomargin">
      <label class="control-label mrg-right10">Show</label>
        <div class="custom-radio">
            <?php $f = 0; ?>
            @foreach($offerstatus as $key=> $status)
            <label for="prod{{$status}}">
                <input {{(@$f==0)?'checked':''}} id="prod{{$status}}" type="radio" value="{{$key}}" name="status"><span></span>{{$status}}
            </label>
            <?php $f++; ?>
            @endforeach
        </div>
    </div>
    <!--Advance Filter End--> 
    <!--Main Filter Start-->
     <div class="main-filter clearfix"> 
      <label class="buylabel">Select Period</label>
      <div class="selectbox buyselectbox">
        {!! Form::select('created_at', (['0' => 'Select a time period'] + $periodTime), null, ['class' => 'selectpicker']) !!}
      </div>
      
      <div class="input-search width200">
        <input type="text" class="form-control padd-right35" placeholder="Enter product name" name="search_offer">
        <a href="#" class="search-icon"></a> </div>
        <input class="btn btn-primary" title="Submit" value="Submit" name="offer_list_filter" type="submit">
      </div>

    <!--Main Filter End-->
   
    <!--Buy Offer Table Start-->
    
      <table class="table table-bordered" id="buyoffer-table" >
        <thead>
          <tr>
            <th>Products Description</th>
            <th>Buy It Now Price($)</th>
            <th>Offered Price($)</th>
            <th>No. of Attempted</th>
            <th>Status</th>
            <th>Counter Offer($)</th>
            <th>Product Quantity</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <div class="modal in" role="dialog" id="offer-modal" tabindex="-1" aria-hidden="true" >
        <div class="modal-dialog modal-lg" role="document" id="popup_div">
            <div class="modal-content">
               
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
    
    <!--Buy Offer Table End--> 
    </div>
  </div>
  <!--Rightside End --> 
       
@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Buyer Offer'=>'']])
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

    var oTable = $('#buyoffer-table').DataTable({
        /*fnServerData: function (sSource, aoData, fnCallback) {
            aoData.push({"name": "sSearch", "value": $('input[name=product_global_search]').val()});
            $.ajax({
                "dataType": 'json',
                "type": "POST",
                "url": 'http://inspreefront.com/sell/product/productsDatatableList',
                "data": aoData,
                "success": fnCallback
            });
        },*/
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
            url: "{{route('buyerOfferDatatableList')}}",
            data: function (d) {
                d.status      = $('input[name=status]:checked').val();
                d.created_at  = $('select[name=created_at] option:selected').val();
                d.name        = $('input[name=search_offer]').val();
            }
        },
        columns: [
            {data: 'name', name: 'name',width: '170px'},
            {data: 'base_price', name: 'products.base_price',width: '80px'},
            {data: 'offer_price', name: 'offer_price',width: '70px',searchable: false, orderable: false},
            {data: 'number_of_attempted', name: 'number_of_attempted',width: '70px',searchable: false, orderable: false},
            {data: 'offer_status', name: 'offer_status',width: '70px',searchable: false, orderable: false},
            {data: 'counter_offer', name: 'counter_offer',width: '70px',searchable: false, orderable: false},
            {data: 'offer_quantity', name: 'offer_quantity',width: '70px',searchable: false, orderable: false},
            {data: 'action', name: 'action',width: '110px',searchable: false, orderable: false},
        ],
         "initComplete": function(settings, json) {

            $(".counterModel").on('click',function(ev) {            
                ev.preventDefault();
                var target  = $(this).attr("href");
                var modalId = $(this).data("target");
                var a_type  = $(this).attr("a_type");
                
                if(a_type == 'view')
                {
                    $('#popup_div').removeClass('modal-lg');
                }else if(!$('#popup_div').hasClass('modal-lg'))
                {
                    $('#popup_div').addClass('modal-lg');
                }
                
                // load the url and show modal on success
                $(modalId+" .modal-body").load(target, function() { 
                     $(modalId).modal("show"); 
                });
            });    
            
        }   
        /*    
        'columnDefs': [{'targets': 0,'className': 'dt-body-center','render': function (data, type, full, meta) {
                    return '<div class="custom-checkbox"><label><input type="checkbox" class="product_select_multiple" name="product_select_multiple[]" value="' + $('<div/>').text(data).html() + '"><span></span>!</label></div>';
                }
            }],*/
        //buttons: [{"extend": "collection", "text": "<i class=\"fa fa-download\"><\/i> Export", "buttons": ["csv", "excel", "pdf"]}, "print", "reset", "reload"]
    });

    $('#search-form').on('submit', function (e) {
        var form = this;
        // Iterate over all checkboxes in the table
        oTable.$('input[class=product_select_multiple]').each(function () {
            // If checkbox doesn't exist in DOM
            if (!$.contains(document, this)) {
                // If checkbox is checked
                if (this.checked) {
                    // Create a hidden element 
                    $(form).append(
                      $('<input>')
                      .attr('type', 'hidden')
                      .attr('name', this.name)
                      .val(this.value)
                      );
                }
            }
        });
    });

    $('input[name=offer_list_filter]').on("click", function (e) {
        //oTable.draw();
        $('#buyoffer-table').DataTable().ajax.reload();
        e.preventDefault();
    });

    $('.dataTables_filter input').remove();
    $('.dataTables_filter').remove();

</script>
@endpush