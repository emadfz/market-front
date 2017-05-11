@extends('front.buy.layout')

@section('pageContent')

<div class="rightcol-bg clearfix sell-addproduct">
    <h4>Product Auction Listing</h4>
    <!--Advance Filter Start-->
<!--    <div class="advance-filter clearfix">
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-2 control-label padd-topnone">Show</label>
                <div class="col-sm-10">
                    <div class="custom-radio">
                        <label for="all">
                            <input  id="all" type="radio" value="all" name="status"><span></span>All
                        </label>                        
                        <label for="won">
                            <input  id="won" type="radio" value="won" name="status"><span></span>Won
                        </label>                        
                    </div>
                </div>
            </div>            
            <div class="form-group">
                <label class="col-sm-2 control-label">Select Period</label>
                <div class="col-sm-10">
                    <div class="selectbox col-sm-5">
                        {!! Form::select('period', config('project.buyaction_filters'), null, ['class'=>'selectpicker']) !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-btnblock clearfix text-right">
            <input type="submit" title="Submit" class="btn btn-primary" value="Submit" name="product_list_filter" />
        </div>
    </div>-->
    <!--Advance Filter End-->
    <!--Main Filter Start-->
<!--    <div class="main-filter clearfix">
        <a href="javascript:;" title="Active" id="filterActive" class="btn btn-primary">active</a>
        <a href="javascript:;" title="Inactive" id="filterInactive" class="btn btn-primary">inactive</a>
        <div class="filtericon"> <a href="#" class="print"></a> <a href="#" class="download"></a> <a href="#" class="upload"></a> </div>
        
    </div>-->
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

                <th class="col2">Product Image</th>
                <th class="col3" width="20%">Product Info</th>
                <th class="col4" width="5%">Bids</th>
                <th class="col4">Status</th>
                <th class="col5">Price ($) + Shipping Cost</th>
                <th class="col6">Expiration</th>                
                <th class="col15">Actions</th>

            </tr>
        </thead>
        <tbody></tbody>
    </table>
    <!--Sell Product Listing Table End--> 
</div>
<input type="raidio"/>
<!--Rightside End --> 
@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Product listing'=>'']])
@endpush


@push('styles')

@endpush

@push('scripts')
<script src="{{ asset('assets/front/js/jquery.plugin.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery.countdown.js') }}" type="text/javascript"></script>
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
       
        oLanguage: {sLengthMenu: "<select class='selectpicker'><option value='10'>10</option><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option><option value='-1'>All</option></select>"},
        
        processing: true,
        serverSide: false   ,
        /*responsive: true,*/
        ajax: {
            type: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}')
            },
            url: "{{route('buyBidsProductDatatableList')}}",
            data: function (d) {
                d.status = $('input[name=status]:checked').val();
                d.period = $('select[name=period] option:selected').val();                
            }
        },
        columns: [
            {data: 'id', name: 'products.id', searchable: true,orderable: false},
            {data: 'image', name: 'image', orderable: false, searchable: true},
            {data: 'name', name: 'name',orderable: false,searchable: true},
            {data: 'bids', name: 'bids',orderable: false,searchable: true},
            {data: 'status', name: 'status', searchable: true, orderable: false},
            {data: 'base_price', name: 'base_price', searchable: true},
            {data: 'expiration', name: 'expiration',searchable: true},
            {data: 'action', name: 'action', orderable: false, searchable: false,width:'20%'}
            
            
        ],
        fnRowCallback: function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
        
             if(aData.expiration=="Auction ended"){
                $('td:eq(6)', nRow).html('<b style="color:blue">Auction ended</b>');
             }
             else{
                //aData.expiration=new Date(aData.expiration);
                
                $('td:eq(6)', nRow).countdown(
                     {
                        until: new Date(aData.expiration),
                        format: 'd H M S',
                        layout: '{dn} {dl}  {hn} {hl} {mn} {ml} {sn} {sl} ',
                        onExpiry: function(){$('td:eq(6)', nRow).html('<b style="color:blue">Auction ended</b>');},
                    }
                )            
             }
        },
        'columnDefs': [{'targets': 0,'className': 'dt-body-center','render': function (data, type, full, meta) {
                    return '<div class="custom-checkbox"><label><input type="checkbox" class="product_select_multiple" name="product_select_multiple[]" value="' + $('<div/>').text(data).html() + '"><span></span>!</label></div>';
                }
            }],
        //buttons: [{"extend": "collection", "text": "<i class=\"fa fa-download\"><\/i> Export", "buttons": ["csv", "excel", "pdf"]}, "print", "reset", "reload"]
    });

     $('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
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
    function redirectToCheckout(){
        setTimeout(function(){
            window.location.href='{{URL("/cart")}}';
        },3000)
    }
</script>
@endpush
