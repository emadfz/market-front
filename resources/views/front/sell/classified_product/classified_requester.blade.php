@extends('front.sell.layout')

@section('pageContent')

<div class="rightcol-bg clearfix">
  <div class="equal-column">
    <h4>Classified Details</h4>
    <!--Sell Classified Details Table Start-->
    <table class="table table-bordered classified-detail mobile-table">
        <tbody>
          <tr>
            <td class="col1">classified ID</td>
            <td class="col2" data-title="Classified ID">{{ $classifiedID }}</td>
            <td class="col1">classified name</td>
            <td class="col2" data-title="Classified Name">{{ $products['name'] }}</td>
          </tr>
          <tr>
            <td class="col1">status</td>
            <td class="col2" data-title="Status">{{ $products['status'] }}</td>
            <td class="col1">No of requests</td>
            <td class="col2" data-title="No of requests">{{ $products->requestPreview->count() }}</td>
          </tr>
          <tr>
            <td class="col1">Expiration</td>
            <td class="col2" data-title="No of requests">{{ $products->requestPreview->count() }}</td>
          </tr>
        </tbody>
    </table>
    <div class="table-responsive">
      <table class="table table-bordered classified-detail-table mobile-table" width="100%" id="requesterList">
        <thead>
          <tr>
            <th class="col1">Buyer ID</th>
            <th class="col2">Preview</th>
            <th class="col3">Buyer Contact Info</th>
            <th class="col4">Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>

    <div class="modal in" role="dialog" id="message-modal" tabindex="-1" aria-hidden="true" >
      <div class="modal-dialog" role="document">
        
          <div class="modal-content">
            <div class="modal-body">
                
            </div>
        </div>
      </div>
    </div>

    <div class="modal in" role="dialog" id="feedback-modal" tabindex="-1" aria-hidden="true" >
      <div class="modal-dialog" role="document">
          <a href="#" class="close" data-dismiss="modal">close</a>
          <div class="modal-content">
            <div class="modal-header">
             
            </div>
            <div class="modal-body">
                
            </div>
          </div>
      </div>
    </div>

    </div>
    <!--Sell Classified Details Table End--> 
  </div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.css">
   
</div>

@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['Product listing'=>'']])
@endpush

@push('styles')

@endpush

@push('scripts')
<script src="http://cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script>
<script>
function LoadCkeditor(){
      CKEDITOR.replace("ckeditor", {
        uiColor: "#F5F5F5",
        toolbar: "standard",
        toolbarLocation: 'bottom',
        scayt_autoStartup: false,
        enterMode: CKEDITOR.ENTER_BR,
        resize_enabled: true,
        disableNativeSpellChecker: false,
        htmlEncodeOutput: false,
        height: 140,
        removePlugins: 'elementspath',
        editingBlock: false,
        toolbarGroups:[{"name": "basicstyles", "groups": ["basicstyles", "cleanup"]},{"name": "links", "groups": ["links"]},{"name": "document", "groups": ["mode"]}],
        removeButtons: 'Source,RemoveFormat,Superscript,Anchor,Styles,Specialchar'
      });
    }

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.2.0/jquery.rateyo.min.js"></script>

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

    var oTable = $('#requesterList').DataTable({
        oLanguage: {sLengthMenu: "<select class='selectpicker'><option value='10'>10</option><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option><option value='-1'>All</option></select>"},
        processing: true,
        serverSide: true,
        /*responsive: true,*/
        ajax: {
            type: 'POST',
            beforeSend: function (xhr) {
              xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}')
            },
            url: "{{route('datatableRequesterList',[encrypt($classifiedID)])}}",
            data: function (d) {}
        },
        columns: [
            {data: 'user_id', name: 'user_id', searchable: false,orderable: false,},
            {data: 'preview_date', name: 'preview_date',searchable: false,orderable: false},
            {data: 'contact', name: 'contact',searchable: false,orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "initComplete": function(settings, json) {

            $(".counterModel").on('click',function(ev) {           
                ev.preventDefault();
                var target  = $(this).attr("href");
                var modalId = $(this).data("target");
                
                
                /*$("#shippingCalculator .modal-body").html('<h3><img src="'+assetsPath+'assets/front/img/loading-spinner-grey.gif" /> Please Wait...</h3>');*/
                // load the url and show modal on success
                $(modalId+" .modal-body").load(target, function() { 
                    $(modalId).modal("show");
                    //$.unblockUI();
                    LoadCkeditor();
                      $("#rateYo").rateYo({
                        onSet: function (rating, rateYoInstance) {
                            $('#start_val').val(rating);
                        }
                      });
                });
            });    
        }
    });

    $('.dataTables_filter input').remove();
    $('.dataTables_filter').remove();
</script>


@endpush