@extends('front.profile.layout')

@section('pageContent')
<!--Rightside Start -->
<div class="rightcol-bg clearfix profilepage">
    @include('front.profile.myprofile.partial._form',['model' => $getUserData])
    <div class="modal addreport" id="changepassword" role="basic" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content clearfix">
                    <div class="modal-inner clearfix">
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

        $("body").delegate('a[data-target=#change_password_ajax_modal_popup]', 'click', function (ev) {
            ev.preventDefault();
            var target = $(this).attr("href");
            // load the url and show modal on success
            $("#changepassword .modal-inner").load(target, function () {
                $("#changepassword").modal("show");
            });

        });
    });
</script>
@endpush