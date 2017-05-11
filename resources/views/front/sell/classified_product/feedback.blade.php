{!! Form::open(['url' => route('sendFeedbackBuyer',['cid' => $classifiedID,'bid'=>$buyerID]),'class'=>'ajax', 'id'=>'sendFeedbackBuyer']) !!}
<input type="hidden" value="" name="start_val" id="start_val">
<div class="modal-inner clearfix">
    <div class="clearfix">
        <div class="col-md-12 col-sm-12">
            <h6 id="make_offer_title">Give Feedback To Buyer</h6>
            <div class="form-horizontal">
                <div class="form-group flex-wrap">
                    <div id="rateYo"></div>
                </div>
            </div>
            <div class="form-horizontal">
                <div class="form-group flex-wrap">
                    <label class="control-label col-md-3">Feedback<span class="required">*</span></label>
                    <div class="col-md-9">
                        {!! Form::textarea('ckeditor', null, ['class'=>'ckeditor form-control', 'rows' => 5, 'cols' => 30]) !!}
                    </div>
                </div>
                <div class="clearfix text-right">
                    <input type="submit" id="send_buyer_feedback" title="Send Feedback" class="btn btn-primary" value="Send Feedback">
                </div>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

@push('scripts')

<script type="text/javascript">

$("#send_buyer_feedback").click(function(ev) {
    var srlz = $('#sendFeedbackBuyer').serialize();
    $.ajax({
        url: "",
        data: srlz,
        dataType: 'json',
        type: 'POST',
        success: function (dataofconfirm) {
           
        },
        error: function (data, statusText, xhr, $form) {
            // Form validation error.
            if (422 == data.status) 
            {
                processFormErrors($form, $.parseJSON(data.responseText));
                return;
            }
            toastr.error('Whoops! It looks like something went wrong on servers.\n\Please try again, or contact support if the problem persists.');
        }
    });
});

function processFormErrors($form, errors) {

    $.each(errors, function (index, error) {
        
        if ((index.indexOf(".") >= 0)) {
            var selector = '.' + index.replace(/\./g, "\\.");
            $(selector, $form).after('<div class="help-block error">' + error + '</div>').parent().addClass('has-error');
        } else {
            var $input = $(':input[name=' + index + ']', $form);
            if (index == 'global_form_message') {
                toastr.error(error);
            } else if (index == 'report_value') {
                $('.error_forum_report').after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($input.prop('type') === 'file') {
                $('#input-' + $input.prop('name')).after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($("textarea[name=" + index + "]").hasClass("ckeditor")) {
                // for textarea having class ckeditor
                $("textarea[name=" + index + "]").next().after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($("select[name=" + index + "]").hasClass("selectpicker")) {
                // for select box having class selectpicker
                $input.closest("div.btn-group.bootstrap-select").after('<div class="help-block error">' + error + '</div>').closest("div").parent().addClass('has-error');
            } else {
                if ($input.closest("form").hasClass("custom-wo-public")) {
                    $input.closest('.input-icon').after('<div class="help-block error">' + error + '</div>').parent().addClass('has-error');
                } else {
                    if ($input.prop('type') == 'checkbox') {
                        // for checkbox
                        $input.next().next().after('<div class="help-block error">' + error + '</div>').closest("div").addClass('has-error');
                    } else {
                        $input.after('<div class="help-block error">' + error + '</div>').closest("div").addClass('has-error');
                    }
                }
            }
        }
    });
}

</script>

@endpush 