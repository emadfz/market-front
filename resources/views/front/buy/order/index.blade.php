@extends('front.buy.layout')

@section('pageContent')

<!--Rightside Start -->
          <div class="rightcol-bg clearfix  buy-orderhistory">
          <div class="equal-column">
            <h4>Order History</h4>
            <!--Main Filter Start-->
             <div class="main-filter clearfix"> 
              <label class="buylabel">Select Period</label>
              <div class="selectbox buyselectbox">
              	<select class="selectpicker">
                	<option>Last 24 Hours</option>
                	<option>Last 12 Hours</option>
                	<option>Last 1 Hours</option>                                        
                </select>
              </div>
              <div class="input-search width200">
                  <input type="text" class="form-control padd-right35" id="searchval" placeholder="Search" onchange="getsearch(this);">
                <a href="#" class="search-icon"></a> </div>
              </div>
            <!--Main Filter End-->
           
            <!--Buy order history Table Start-->
            
              <table class="table table-bordered selllist-table common-datatable" id="buyerProductsListing">
        <thead>
            <tr>

                <th class="col1"></th>

                <th class="col2">Order Id</th>
                <th class="col3">Order Amount</th>
                <th class="col4">Status</th>
                <th class="col6">Order Date</th>
                <th class="col15">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
            
            
            </div>
          </div>
          

@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Product listing'=>'']])
@endpush


@push('styles')

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

    var oTable = $('#buyerProductsListing').DataTable({
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
            url: "{{route('orderDatatableList')}}",
            data: function (d) {
                d.status = $('input[name=status]:checked').val();
                d.category_id = $('select[name=category_id] option:selected').val();
                d.mode_of_selling = $('input[name=mode_of_selling]:checked').val();
                d.checked_products = $('input[class=product_select_multiple]').serialize();
            }
        },
        columns: [
            {data: 'id', name: 'id', searchable: false,orderable: false,},
            {data: 'shoppingcart_identifier', name: 'shoppingcart_identifier'},
            {data: 'order_amount', name: 'order_amount'},
            {data: 'order_status', name: 'order_status'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
                
        'columnDefs': [{'targets': 0,'className': 'dt-body-center','render': function (data, type, full, meta) {
                    return '<div class="custom-checkbox"><label><input type="checkbox" class="product_select_multiple" name="product_select_multiple[]" value="' + $('<div/>').text(data).html() + '"><span></span>!</label></div>';
                }
            }],
        //buttons: [{"extend": "collection", "text": "<i class=\"fa fa-download\"><\/i> Export", "buttons": ["csv", "excel", "pdf"]}, "print", "reset", "reload"]
    });

    // Handle click on "Select all" control
    $('#productSelectAll').on('click', function () {
        // Get all rows with search applied
        var rows = oTable.rows({'search': 'applied'}).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[class=product_select_multiple]', rows).prop('checked', this.checked);
    });

    // Handle click on checkbox to set state of "Select all" control
    $('#sellerProductsListing tbody').on('change', 'input[class=product_select_multiple]', function () {
        // If checkbox is not checked
        if (!this.checked) {
            var el = $('#productSelectAll').get(0);
            // If "Select all" control is checked and has 'indeterminate' property
            if (el && el.checked && ('indeterminate' in el)) {
                // Set visual state of "Select all" control 
                // as 'indeterminate'
                el.indeterminate = true;
            }
        }
    });

    // Handle form submission event
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

    $('input[name=product_list_filter]').on("click", function (e) {
        //oTable.draw();
        $('#sellerProductsListing').DataTable().ajax.reload();
        e.preventDefault();
    });

    /*$('input[name=product_global_search]').on("keyup", function(e){
     $('#sellerProductsListing').DataTable().ajax.reload();
     e.preventDefault();
     });*/
function cancel_order(id){
if (confirm('Are you sure want to Cancel this order ?') == true) {
    $.ajax({
            url: '{{route("cancel_order")}}',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', id: id, submit: true},
            success: function (r) {
                $("#status_id_"+id).html('canceled');        
            },
            error: function (data) {
            }
        });
}        
}
function getsearch(text){
    text=$("#searchval").val();
    oTable
        .search( text )
        .draw();
}
</script>
@endpush
