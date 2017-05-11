@extends('front.buy.layout')

@section('pageContent')

<!--Rightside Start -->
<div class="rightcol-bg clearfix  buy-ads">
    <div class="equal-column">
      <h4>Purchased Ads</h4>
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
          <input type="text" class="form-control padd-right35" placeholder="Search">
          <a href="#" class="search-icon"></a> </div>
        <a href="{{ route('postAdd') }}" class="btn btn-primary requestbtn" title="Post AD">Post AD</a>  
      </div>
      <!--Main Filter End--> 
      
      <!--Buy ads Table Start-->
      
      <table class="table table-bordered" id="buyads-table">
        <thead>
          <tr>
            <th>AD ID</th>
            <th>AD Name</th>
            <th>Location</th>
            <th>Type</th>
            <th>Status</th>
            <th>Start Date</th>
            <th>Expiration Date</th>
            <th>Paid($)</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
      
      <!--Buy ads Table End--> 
    </div>
</div>

<div class="modal" id="large" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
           <div class="modal-inner clearfix">
                <a href="#"  onclick="hide_preview()" class="close" data-dismiss="modal" aria-hidden="true"></a>
                <h6 class="modal-title">{!!trans("message.preview")!!}</h6>
              <div class="clearfix">
                  <div class="slides-outer">
                    <a href="#" id="main_box_a"><img src="{{ URL("/assets/front/img/no-image-main.png" ) }}" id="main_box" alt="No Image" class="advrt_image " width="820" height="450"></a>
                  </div>
                <div class="small-banner">
                    <a href="#"><img src="{{ URL("/assets/front/img/small-noimage.png" ) }}"  alt="No Image" class="advrt_image" height="143" width="340"></a>
                    <a href="#" id="banner_a"><img src="{{ URL("/assets/front/img/small-noimage.png" ) }}" id="banner" alt="No Image" class="advrt_image" height="143" width="340"></a>
                    <a href="#"><img src="{{ URL("/assets/front/img/small-noimage.png" ) }}" alt="No Image" class="advrt_image" height="143" width="340"></a>
                </div>
              </div>
            </div>
        </div>
    </div>
    <!-- /.modal-dialog -->
</div>


   <div class="modal" id="advertisement_detail_modal" tabindex="-1">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-inner clearfix">
                    <a href="#" class="close" data-dismiss="modal">close</a>                    
                            <div class="modal-body">
                                
                            </div>
                </div>
            </div>
        </div>
    </div>


<!--Rightside End --> 
@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Purchased Ads'=>'']])
@endpush
        
@push('scripts')
<script type="text/javascript">
$( "body" ).delegate( ".advertisement", "click", function(ev) {
 
// $(".advertisement").click(function(ev) {

  
        //$.blockUI({ message: '<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>' });
        ev.preventDefault();
        var target = $(this).attr("href");
        var modalId = $(this).data("target");
        
        $(modalId+" .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');
        // load the url and show modal on success
        $(modalId+" .modal-body").load(target, function() { 
             $(modalId).modal("show"); 
             //$.unblockUI();
        });


        
    });


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
        order: [[0, 'desc']],
    });

    var oTable = $('#buyads-table').DataTable({
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
            url: "{{route('addDatatableList')}}",
            data: function (d) {
                d.created_at  = $('select[name=created_at] option:selected').val();
            }
        },
        columns: [
            {data: 'id', name: 'id',width: '20px'},
            {data: 'add_name', name: 'add_name',width: '40px'},
            {data: 'location', name: 'location',width: '60px',searchable: false, orderable: false},
            {data: 'type', name: 'type',width: '70px',searchable: false, orderable: false},
            {data: 'add_status', name: 'add_status',width: '60px',searchable: false, orderable: false},
            {data: 'start_date', name: 'start_date',width: '70px',searchable: false, orderable: false},
            {data: 'end_date', name: 'end_date',width: '70px',searchable: false, orderable: false},
            {data: 'paid', name: 'paid',width: '110px',searchable: false, orderable: false},
            {data: 'action', name: 'action',width: '120px',searchable: false, orderable: false},
        ],
            
        /* 'columnDefs': [{'targets': 0,'className': 'dt-body-center','render': function (data, type, full, meta) {
                    return '<div class="custom-checkbox"><label><input type="checkbox" class="product_select_multiple" name="product_select_multiple[]" value="' + $('<div/>').text(data).html() + '"><span></span></label></div>';
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

    
    $('.dataTables_filter input').remove();
    $('.dataTables_filter').remove();

function pause_add(id,add_type){
  
  if (confirm('Are you sure want to '+add_type+' this add ?') == true) {
    //alert(add_type);
    $.ajax({
          url: '{{route("changeAddStatus")}}',
          type: 'POST',
          dataType: 'json',
          data: {method: '_POST', id: id, type:add_type, submit: true},
          success: function (r) {
                
                location.reload();
          },
          error: function (data) {
          
          }
      });
  }        
}


function ad_preview(id,ad_type){

  $('.advrt_image').attr('src', '{{ URL("/assets/front/img/no-image-main.png" ) }}');
  var type = ad_type; 
  var src = $('#image_'+id).attr('full_image_path');

  if(type == 'Main_Box')
  {
      $('#main_box').attr('src', src);
      $('#main_box_a').attr('href', $( "#advr_url" ).val());
  }
  else if(type == 'Banner')
  {
      $('#banner').attr('src', src);
      $('#banner_a').attr('href', $( "#advr_url" ).val());
  }


  $('#large').show();

}

function hide_preview(){

    $('#large').hide();
};

/**/

</script>
@endpush