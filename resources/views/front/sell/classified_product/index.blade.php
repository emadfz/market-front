@extends('front.sell.layout')

@section('pageContent')
<div class="rightcol-bg clearfix sell-classifiedlist">
    <div class="equal-column">
        <h4>Classified Listing</h4>
            <ul class="overview-classified clearfix">
                <li>Total Classified:<span>{{ $totalClassified }}</span></li>
                <li>No of request:<span>{{ $totalRequester }}</span></li> 
            </ul>
        <!--Advance Filter Start-->
        <div class="advance-filter clearfix">
            <div class="form-horizontal">
                <div class="form-group">
                  <label class="col-md-1 col-sm-2 control-label">Search</label>
                  <div class="col-sm-4">
                    <input type="text" class="form-control" placeholder="Enter product name" name="search_classified">
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
                            {!! Form::select('updated_at', (['0' => 'Select a time period'] + @$periodTime), null, ['class'=>'selectpicker']) !!}
                        </div>
                    <label class="col-md-1 col-sm-2 control-label">Category</label>
                        <div class="selectbox col-sm-4">
                            {!! Form::select('category_id', @$categories, null, ['class'=>'selectpicker']) !!}
                        </div>
                         <div class="col-md-2 col-sm-12">
                    <input type="submit" title="Submit" class="btn btn-primary" value="Submit" name="product_list_filter" />
            </div>
                </div>
            </div>
           
        </div>
        <!--Advance Filter End-->
        <!--Main Filter Start-->
        <div class="main-filter clearfix">
            <a href="javascript:;" title="Active" id="filterActive" class="btn btn-primary">active</a>
            <a href="javascript:;" title="Inactive" id="filterInactive" class="btn btn-primary">inactive</a>
            <!-- <div class="filtericon"> <a href="#" class="print"></a> <a href="#" class="download"></a> <a href="#" class="upload"></a> </div> -->
            
            <!-- <div class="input-search width200">
                <input type="text" class="form-control padd-right35" id="" placeholder="Search" name="product_global_search">
                <a href="#" class="search-icon"></a>
            </div> -->

            <a href="{{route("createClassifiedProduct")}}" title="Add Classified Product" class="btn btn-primary pull-right">add classified product</a>
        </div>
        <!--Main Filter End-->
        <!--Sell Product Listing Table Start-->
        <div class="table-responsive">
            <table class="table table-bordered classifiedlist-table" id="sellerProductsListing">
                <thead>
                    <tr>
                        <!--<th class="col1"><div class="custom-checkbox"><label><input type="checkbox" value="All"><span></span>!</label></div></th>-->
                        <th class="col1">
                            <div class="custom-checkbox"><label><input type="checkbox" name="product_select_all" value="1" id="productSelectAll"><span></span>!</label></div>
                        </th>
                        
                        <th class="col2">Name</th>
                        <th class="col3">Product ID</th>
                        <th class="col4">Category</th>
                        <th class="col5">No Of Requester</th>
                        <th class="col6">Status</th>
                        <th class="col7">Expiration</th>
                        <th class="col13">Viewers Per Day</th>
                        <th class="col14">Total Rating</th>
                        <th class="col15">Action</th>

                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    <!--Sell Product Listing Table End-->
    </div> 
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
        oLanguage: {sLengthMenu: "<select class='selectpicker'><option value='10'>10</option><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option><option value='-1'>All</option></select>"},
        processing: true,
        serverSide: true,
        /*responsive: true,*/
        ajax: {
            type: 'POST',
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}')
            },
            url: "{{route('classifiedDatatableList')}}",
            data: function (d) {
                d.name              = $('input[name=search_classified]').val();
                d.status            = $('input[name=status]:checked').val();
                d.category_id       = $('select[name=category_id] option:selected').val();
                d.updated_at        = $('select[name=updated_at] option:selected').val();
                d.checked_products  = $('input[class=product_select_multiple]').serialize();
            }
        },
        columns: [
            {data: 'id', name: 'classified_products.id', searchable: false,orderable: false,},
            {data: 'product_name', name: 'product_name',searchable: false,orderable: false},
            {data: 'id', name: 'classified_products.id'},
            {data: 'product_category.text', name: 'category.text', searchable: true, orderable: true},
            {data: 'No_of_requester', name: 'No_of_requester'},
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

    $("#productSelectAll").prop('checked', false);
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

    $('.dataTables_filter input').remove();
    $('.dataTables_filter').remove();

    $('#filterActive').click(function(){
        var srlz = $('.product_select_multiple').serialize();
        
        $.ajax({
            url: "{{route('updateClassifiedStatus', ['Active'])}}",
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
            url: "{{route('updateClassifiedStatus', ['Inactive'])}}",
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
