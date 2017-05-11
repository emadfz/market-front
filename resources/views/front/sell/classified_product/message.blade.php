<!--Counter Offer Modal Start-->

{!! Form::open(['url' => route('sendMessageBuyer',['email'=>encrypt($email),'id' => $classifiedID]),'class'=>'ajax', 'id'=>'sendMessageBuyer']) !!}
<div class="modal-inner clearfix">
    <a href="#" class="close" data-dismiss="modal">close</a>
    <h6 id="make_offer_title">Send Message To Buyer</h6>
    <div class="clearfix mrg-top20">
        <div class="col-md-12 col-sm-12">
            
            <div class="form-horizontal" id="make_offer_div">
                <div class="form-group">
                    <label class="control-label col-sm-3">To</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="to" value="{{ $email }}" readonly="readonly">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-3">Subject</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="subject" value="">
                    </div>
                </div>

                
                        <div class="form-group flex-wrap">
                            <label class="control-label col-sm-3">Body<span class="required">*</span></label>
                            <div class="col-sm-9">
                                {!! Form::textarea('ckeditor', null, ['class'=>'ckeditor form-control', 'rows' => 5, 'cols' => 30]) !!}
                            </div>
                        </div>
                
                <div class="clearfix text-right">
                    <input type="submit" id="send_buyer_message" title="Send Message" class="btn btn-primary" value="Send Message">
                </div>
            </div>
        </div>
    </div>
    
</div>

{!! Form::close() !!}

<!--Counter Offer Modal End-->

@push('scripts')
<script type="text/javascript">
$("#send_buyer_message").click(function(ev) {
    var srlz = $('#sendMessageBuyer').serialize();
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