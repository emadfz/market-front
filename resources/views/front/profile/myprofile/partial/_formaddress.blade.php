<div class="equal-column">
    <h4 class="bghead">Address Book</h4>
    <!--Profile Business Information Start-->

    <div class="address-block">

        @foreach($getUserBillingData as $data)
        <h5 class="blacktitle">Billing Address</h5>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="address-bg clearfix">
                    <p><span>{{$data->user->first_name}} {{$data->user->last_name}}</span>
                        <span>{{$data->address_1}}</span>
                        <span>{{$data->address_2}}</span>
                        <span>{{$data->country->country_name}} {{$data->postal_code}}</span>
                        <span>{{$data->state->state_name}}, {{$data->city->city_name}}</span></p>
                    <div class="address-link">
                        <a href="{{route('getEditAddressPopup', [$data->id])}}" data-target="#edit_address_ajax_modal_popup" data-toggle="modal" title="Edit Address">Edit Address</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <h5 class="blacktitle clearfix">Primary shipping address 
            <a href="{{route('getEditAddressPopup','')}}" class="btn btn-primary pull-right" data-target="#edit_address_ajax_modal_popup" data-toggle="modal" title="Add New Address">Add New Address</a>
        </h5>
        <hr>
        <div class="row">
            <div class="col-md-6">
                @if(!empty($getUserShippingData->toArray()))
                @foreach($getUserShippingData as $data)
                <div class="address-bg clearfix">
                    <p><span>{{$data->user->first_name}} {{$data->user->last_name}}</span>
                        <span>{{$data->address_1}}</span>
                        <span>{{$data->address_2}}</span>
                        <span>{{$data->country->country_name}} {{$data->postal_code}}</span>
                        <span>{{$data->state->state_name}}, {{$data->city->city_name}}</span></p>
                    <div class="address-link">
                        <a href="{{route('getEditAddressPopup', [$data->id])}}" data-target="#edit_address_ajax_modal_popup" data-toggle="modal" title="Edit Address">Edit Address</a>
                        
                        <a data-delete_remote="{{route('deleteAddress', encrypt($data->id))}}" class="deleteAttributeSet" title="Delete">Delete</a>

                    </div>
                </div>
                @endforeach
                @else
                <p class="nodata">No Record Found</p>
                @endif
            </div>
        </div>  


    </div>




    <!--Profile Business Information End--> 
</div>

<!-- Modal Dialog -->
<div class="modal" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <a href="#" class="close" data-dismiss="modal">{{trans('profile.label.close')}}</a>
                <h4 class="modal-title">{{trans("profile.label.delete")}}</h4>
            </div>
            <div class="modal-body nopadding">
                <p>{{trans('profile.label.deletebody')}}</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="cancel-link" data-dismiss="modal">{{trans('profile.label.cancel')}}</a>
                <button type="button" class="btn btn-danger" id="confirmDeleteAttributeSet">{{trans('profile.label.deletebtn')}}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var deleteUrl = '';
    $("body").delegate('.deleteAttributeSet', 'click', function (e) {
        e.preventDefault();
        $('#confirmDelete').modal('show');
        deleteUrl = $(this).data('delete_remote');
    });
   
    $("body").delegate('#confirmDeleteAttributeSet', 'click', function (e) {
        $.ajax({
            url: deleteUrl,
            type: 'DELETE',
            dataType: 'json',
            data: {method: '_DELETE', submit: true},
            success: function (r) {
                if (r.success == 1) {
                    $('#confirmDelete').modal('hide');
                    toastr.success(r.msg);
                   window.location = r.redirectUrl;
                } else if (r.success == 0) {
                    toastr.error(r.msg, "{{ trans('message.failure') }}", {timeOut: 10000});
                    $('#confirmDelete').modal('hide');
                }
            },
            error: function (data) {
                if (data.status === 422) {
                    toastr.error("{{ trans('message.failure') }}");
                }
            }
        });
    });
    //});

</script>
@endpush
