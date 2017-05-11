@extends('front.profile.layout')

@section('pageContent')
<!--Rightside Start -->
<div class="rightcol-bg clearfix profile-addressbook">
    @include('front.profile.myprofile.partial._formaddress')
    <div class="modal addreport" id="editaddress" role="basic" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-body nopadding">
                    <img src="{{ asset('assets/front/img/loading-spinner-grey.gif') }}" alt="{{ trans('form.loading') }}" class="loading">
                    <span> &nbsp;&nbsp;{{ trans('form.loading') }} </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Rightside End --> 


@endsection

@push('breadcrumb')
@include('front.reusable.breadcrumb',['breadcrumbs' => ['abc'=>'vvv','xyz'=>'']])
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {

        $("body").delegate('a[data-target=#edit_address_ajax_modal_popup]', 'click', function (ev) {
            ev.preventDefault();
            var target = $(this).attr("href");
            // load the url and show modal on success
            $("#editaddress .modal-body").load(target, function () {
                $("#editaddress").modal("show");
                $('select#country').selectpicker('refresh');
                $('select#state').selectpicker('refresh');
                $('select#city').selectpicker('refresh');
            });

        });
    });
</script>
@endpush