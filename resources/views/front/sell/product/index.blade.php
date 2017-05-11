@extends('front.sell.layout')

@section('pageContent')
<div class="rightcol-bg clearfix sell-addproduct">
    <h4>Product Listing</h4>
    <!--Advance Filter Start-->
    <div class="advance-filter clearfix">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label padd-topnone">Show</label>
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
                <label class="col-sm-2 control-label padd-topnone">Selling Mode</label>
                <div class="col-sm-10">
                    <div class="custom-radio">
                        <?php $ff = 0; ?>
                        @foreach($productMOS as $pMOS)
                        <label for="prod-{{$pMOS}}">
                            <input {{(@$ff==0)?'checked':''}} id="prod-{{$pMOS}}" type="radio" value="{{$pMOS}}" name="mode_of_selling" class="mode_of_selling"><span></span>{{$pMOS}}
                        </label>
                        <?php $ff++; ?>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="form-group">
                <!--<label class="col-sm-2 control-label">Period</label>-->
                <label class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10">
                    <div class="selectbox col-sm-5">
                        {!! Form::select('category_id', @$categories, null, ['class'=>'selectpicker']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-btnblock clearfix text-right">
            <input type="submit" title="Submit" class="btn btn-primary" value="Submit" name="product_list_filter" />
        </div>
    </div>
    <!--Advance Filter End-->
    <!--Main Filter Start-->
    <div class="main-filter clearfix">
        <a href="javascript:;" title="Active" id="filterActive" class="btn btn-primary">active</a>
        <a href="javascript:;" title="Inactive" id="filterInactive" class="btn btn-primary">inactive</a>
        <div class="filtericon"> <a href="#" class="print"></a> <a href="#" class="download"></a> <a href="#" class="upload"></a> </div>
        
        <!-- <div class="input-search width200">
            <input type="text" class="form-control padd-right35" id="" placeholder="Search" name="product_global_search">
            <a href="#" class="search-icon"></a>
        </div> -->

        <a href="{{route("createProduct",['step_one',0])}}" title="Add New Product" class="btn btn-primary pull-right">add new product</a>
    </div>
    <!--Main Filter End-->
    <!--Sell Product Listing Table Start-->

    <table class="table table-bordered selllist-table common-datatable" id="sellerProductsListing">
        <thead>
            <tr>
                <!--<th class="col1"><div class="custom-checkbox"><label><input type="checkbox" value="All"><span></span>!</label></div></th>-->
                <th class="col1">
                    <div class="custom-checkbox"><label><input type="checkbox" name="product_select_all" value="1" id="productSelectAll"><span></span>!</label></div>
                </th>
                <!--<th class="col5">Manufacturer</th>-->
                <!--<th class="col7">Return Applicable</th>
                <th class="col8">Warranty Application</th>-->
                <!--<th class="col10">Created At</th>
                <th  class="col11">Updated At</th>-->

                <th class="col2">Name</th>
                <th class="col3">Product ID</th>
                <th class="col4">Image</th>
                <th class="col4">Category</th>
                <th class="col5">SKU</th>
                <th class="col6">Selling Mode</th>
                <th class="col7">Price</th>
                <th class="col8">Min. Reserved price</th>
                <th class="col9">Max. Product Price </th>
                <th class="col10">Stock Available</th>
                <th class="col11">Status</th>
                <th class="col12">Expiration</th>
                <th class="col13">Viewers Per Day</th>
                <th class="col14">Total Rating</th>
                <th class="col15">Actions</th>

            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <!--Sell Product Listing Table End--> 
</div>
<!--Rightside End --> 
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

    var oTable = $('#sellerProductsListing').DataTable({
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
            url: "{{route('productsDatatableList')}}",
            data: function (d) {
                d.status = $('input[name=status]:checked').val();
                d.category_id = $('select[name=category_id] option:selected').val();
                d.mode_of_selling = $('input[name=mode_of_selling]:checked').val();
                d.checked_products = $('input[class=product_select_multiple]').serialize();
            }
        },
        columns: [
            {data: 'id', name: 'products.id', searchable: false,orderable: false,},
            {data: 'name', name: 'products.name'},
            {data: 'id', name: 'products.id'},
            {data: 'image', name: 'image', orderable: false, searchable: false},
            {data: 'product_category.text', name: 'category.text', searchable: true, orderable: true},
            {data: 'sku_prefix', name: 'sku_prefix', searchable: false},
            {data: 'mode_of_selling', name: 'mode_of_selling'},
            {data: 'base_price', name: 'base_price'},
            {data: 'min_reserved_price', name: 'min_reserved_price'},
            {data: 'max_product_price', name: 'max_product_price'},
            {data: 'stock_available', name: 'stock_available'},
            {data: 'status', name: 'status', searchable: true},
            {data: 'expiration', name: 'expiration', searchable: true},
            {data: 'viewers_per_day', name: 'viewers_per_day', "orderable": false, searchable: true},
            {data: 'total_rating', name: 'total_rating', "orderable": false, searchable: true},
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
    // add placeholder in search text
    $('.dataTables_filter input').attr("placeholder", "enter product name here");

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
        $('#sellerProductsListing').DataTable().ajax.reload();
        e.preventDefault();
    });

    /*$('input[name=product_global_search]').on("keyup", function(e){
     $('#sellerProductsListing').DataTable().ajax.reload();
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
