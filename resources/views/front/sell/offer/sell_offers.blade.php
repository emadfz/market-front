@extends('front.sell.layout')

@section('pageContent')

<div class="rightcol-bg clearfix">
          	<div class="equal-column">
            <h4>Make an Offer Activity</h4>
            <!--Advance Filter Start-->
            <div class="advance-filter clearfix">
              <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-md-1 col-sm-2 control-label">Search</label>
                  <div class="col-sm-4">
                  	<input type="text" class="form-control" placeholder="Enter product name" name="search_offer">
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-md-1 col-sm-2 control-label padd-topnone">Show</label>
                    <div class="col-sm-10">
                        <div class="custom-radio">
                            <?php $f = 0; ?>
                            @foreach($productStatus as $status)
                            <label for="prod{{$status}}">
                                <input {{(@$f==0)?'checked':''}} id="prod{{$status}}" type="radio" value="{{$status}}" name="status"><span></span>{{$status}}
                            </label>
                            <?php $f++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="form-group">
                  <label class="col-md-1 col-sm-2 control-label">Period</label>
                    <div class="selectbox col-sm-4">
                      {!! Form::select('created_at', (['0' => 'Select a time period'] + $periodTime), null, ['class' => 'selectpicker']) !!}
                      
                    </div>
                    <label class="col-md-1 col-sm-2 control-label">Category</label>
                    <div class="selectbox col-sm-4">
                       {!! Form::select('category_id', @$categories, null, ['class'=>'selectpicker']) !!}
                    </div>
                    <div class="col-md-2 col-sm-12">
                    	<input class="btn btn-primary" title="Submit" value="Submit" name="offer_list_filter" type="submit">
                    </div>
                </div>
              </div>
            </div>
            <!--Advance Filter End--> 
            <!--Main Filter Start-->
            <div class="main-filter clearfix"> 
                <a href="#" title="Active" class="btn btn-primary" id="filterActive">active</a> 
                <a href="#" title="Inactive" class="btn btn-primary" id="filterInactive">inactive</a>
                <div class="filtericon"> <a href="#" class="print"></a> <a href="#" class="download"></a> <a href="#" class="upload"></a> </div>
               
            </div>
            <!--Main Filter End--> 
            <!--Sell Product Listing Table Start-->
            <div class="table-responsive">
              <table class="table table-bordered selloffer-table" id="offerproductlisting">
                <thead>
                  <tr>
                    <!-- <th class="col1"><div class="custom-checkbox">
                        <label>
                          <input type="checkbox" value="All">
                          <span></span> !</label>
                      </div>
                    </th> -->

                    <th class="col1">
                        <div class="custom-checkbox"><label><input type="checkbox" name="product_select_all" value="1" id="productSelectAll"><span></span>!</label></div>
                    </th>

                    <th class="col2">Name</th>
                    <th class="col3">Product ID</th>
                    <th class="col4">Category</th>
                    <th class="col5">SKU</th>
                    <th class="col6">Selling mode</th>
                    <th class="col7">Minimum Reserved price</th>
                    <th class="col8">Maximum Product Price </th>
                    <th class="col9">Buyers</th>
                    <!-- <th class="col10">offer start date</th>
                    <th class="col11">status</th>
                    <th class="col12">expiration</th> -->
                    <th class="col13">Product sold</th>
                    <th class="col14">Viewer per day</th>
                    <th class="col15">Total rating</th>
                    <!-- <th class="col15">Created</th> -->
                  </tr>
                </thead>
                <tbody></tbody>

            </table>
            </div>
            <!--Sell Product Listing Table End--> 
</div>
@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Product Offers'=>'']])
@endpush

<!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" type="text/css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" type="text/css">
 -->

@push('scripts')
<!-- <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js" type="text/javascript"></script> -->

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

    var oTable = $('#offerproductlisting').DataTable({
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
            url: "{{route('offerDatatableList')}}",
            data: function (d) {
                d.status            = $('input[name=status]:checked').val();
                d.category_id       = $('select[name=category_id] option:selected').val();
                d.mode_of_selling   = $('input[name=mode_of_selling]:checked').val();
                d.created_at        = $('select[name=created_at] option:selected').val();
                d.name              = $('input[name=search_offer]').val();
            }
        },
        columns: [
            
            {data: 'id', name: 'products.id', searchable: false,orderable: false,},
            {data: 'name', name: 'products.name'},
            {data: 'product_id', name: 'product_id', searchable: false,orderable: false,},
            {data: 'product_category.text', name: 'category.text', searchable: true, orderable: true},
            {data: 'sku_prefix', name: 'sku_prefix', searchable: false},
            {data: 'mode_of_selling', name: 'mode_of_selling'},
            {data: 'min_reserved_price', name: 'min_reserved_price'},
            {data: 'max_product_price', name: 'max_product_price'},
            {data: 'buyer_count', name: 'buyer_count',orderable: false, searchable: false},
            {data: 'product_sold', name: 'product_sold', orderable: false, searchable: false},
            {data: 'viewers_per_day', name: 'viewers_per_day', "orderable": false, searchable: true},
            {data: 'total_rating', name: 'total_rating', "orderable": false, searchable: true},
            /*{data: 'created_at', name: 'offers.created_at'},*/
        ],
        'columnDefs': [{'targets': 0,'className': 'dt-body-center','render': function (data, type, full, meta) {
                    return '<div class="custom-checkbox"><label><input type="checkbox" class="product_select_multiple" name="product_select_multiple[]" value="' + $('<div/>').text(data).html() + '"><span></span>!</label></div>';
                }
        }],
        /*"fnRowCallback": function (nRow, aData, iDisplayIndex) {

                // Bind click event
                $(nRow).click(function() {
                    window.open('http://example.com');

                      //OR

                     window.open(aData.url);
                });

                return nRow;
           },*/

        //buttons: [{"extend": "collection", "text": "<i class=\"fa fa-download\"><\/i> Export", "buttons": ["csv", "excel", "pdf"]}, "print", "reset", "reload"]
    });

    // Handle click on "Select all" control
    $('#productSelectAll').on('click', function () {
        // Get all rows with search applied
        var rows = oTable.rows({'search': 'applied'}).nodes();
        // Check/uncheck checkboxes for all rows in the table
        $('input[class=product_select_multiple]', rows).prop('checked', this.checked);
    });
    // add placeholder in search text
    $('.dataTables_filter input').remove();
    $('#offerproductlisting_filter').remove();

    // Handle click on checkbox to set state of "Select all" control
    $('#offerproductlisting tbody').on('change', 'input[class=product_select_multiple]', function () {
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

    $('input[name=offer_list_filter]').on("click", function (e) {
        $('#offerproductlisting').DataTable().ajax.reload();
        e.preventDefault();
    });

    /*$('input[name=product_global_search]').on("keyup", function(e){
     $('#offerproductlisting').DataTable().ajax.reload();
        e.preventDefault();
    });*/

    $('#filterActive').click(function(){
        var srlz = $('.product_select_multiple').serialize();
        
        $.ajax({
            url: "{{route('updateStatus', ['Active'])}}",
            data: srlz,
            dataType: 'json',
            type: 'POST',
            success: function (dataofconfirm) {
               location.reload(); 
            }
        });
    })

    $('#filterInactive').click(function(){
        var srlz = $('.product_select_multiple').serialize();
        
        $.ajax({
            url: "{{route('updateStatus', ['Inactive'])}}",
            data: srlz,
            dataType: 'json',
            type: 'POST',
            success: function (dataofconfirm) {
                location.reload();   
            }
        });
    })

</script>
@endpush