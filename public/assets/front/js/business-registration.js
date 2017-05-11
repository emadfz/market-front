$(function () {
    /*
     * --------------------
     * Ajaxify those forms
     * --------------------
     * All forms with the 'ajax' class will automatically handle showing errors etc.
     */
    $('form.biz-ajax').ajaxForm({
        delegation: true,
        beforeSubmit: function (formData, jqForm, options) {
            $(jqForm[0]).find('.error.help-block').remove();
            $(jqForm[0]).find('.has-error').removeClass('has-error');

            var $submitButton = $(jqForm[0]).find('input[type=submit]');
            toggleSubmitDisabled($submitButton);
        },
        uploadProgress: function (event, position, total, percentComplete) {
            $('.uploadProgress').show().html('Uploading Images - ' + percentComplete + '% Complete...    ');
        },
        error: function (data, statusText, xhr, $form) {

            // Form validation error.
            if (422 == data.status) {
                processFormErrors($form, $.parseJSON(data.responseText));
                return;
            }

            toastr.error('Whoops! It looks like something went wrong on servers.\n\Please try again, or contact support if the problem persists.');

            var $submitButton = $form.find('input[type=submit]');
            toggleSubmitDisabled($submitButton);

            $('.uploadProgress').hide();
        },
        success: function (data, statusText, xhr, $form) {

            switch (data.status) {
                case 'success':

                    if ($form.hasClass('reset')) {
                        $form.resetForm();
                    }

                    /*
                     * To close the modal after submit, just add one class in form tag
                     */
                    if ($form.hasClass('closeModalAfter')) {
                        //$('.modal, .modal-backdrop').fadeOut().remove();
                        $('.modal, .modal-backdrop').hide();
                    }

                    /*
                     * if you want to close modal popup and redraw datatablelist after updating record from popup(which opens using any action from datatable e.g Edit)
                     * then you have to provide below attribute in form 
                     * 'data-datatable_id' => 'same id of table which you used for datatable list'
                     */
                    /*if ($form.data('datatable_id') != "") {
                     $('#' + $form.data('datatable_id')).DataTable().draw();
                     $('div.modal').modal('hide');
                     }*/

                    var $submitButton = $form.find('input[type=submit]');
                    toggleSubmitDisabled($submitButton);

                    if (typeof data.message !== 'undefined') {
                        showMessage(data.message, data.status);
                    }

                    if (typeof data.runThis !== 'undefined') {
                        eval(data.runThis);
                    }

                    if (typeof data.redirectUrl !== 'undefined') {
                        window.location = data.redirectUrl;
                    }

                    if (typeof data.stepMove !== 'undefined') {
                        businessRegistrationStepNext(data.stepMove);
                    }

                    break;

                case 'error':
                    processFormErrors($form, data.messages);
                    break;

                default:
                    break;
            }

            $('.uploadProgress').hide();
        },
        dataType: 'json'
    });


    /*
     * Business Registration Next step
     */
    function businessRegistrationStepNext(stepNo) {
        switch (stepNo) {
            case 'step2':
                _disableFieldsExceptCurrentStep("div#reg-step1, div#reg-step3, div#reg-step4", 2, true);
                break;
            case 'step3':
                _disableFieldsExceptCurrentStep("div#reg-step1, div#reg-step2, div#reg-step4", 3, true);
                break;
            case 'step4':
                // when move to last 4th step enable all fields
                $("div#reg-step1, div#reg-step2, div#reg-step3").hide().find('input, textarea, button, select').removeAttr('disabled', 'disabled');
                $("ul.registration-step li:nth-child(4)").addClass("active");
                $("div#reg-step4").find("input[type=submit]").val("Register");
                break;
        }
        _enableFieldsOfCurrentStep("div#reg-" + stepNo);
    }

    // call first time, when business registration page renders
    _disableFieldsExceptCurrentStep("div#reg-step2, div#reg-step3, div#reg-step4", 1, true);

    /*
     * Business Registration Back step
     */
    $("a.business-reg-back-step").click(function () {
        businessRegistrationStepBack($(this).data("moveto"));
    })
    function businessRegistrationStepBack(stepNo) {
        switch (stepNo) {
            case 'step1':
                _disableFieldsExceptCurrentStep("div#reg-step2, div#reg-step3, div#reg-step4", 2, false);
                break;
            case 'step2':
                _disableFieldsExceptCurrentStep("div#reg-step1, div#reg-step3, div#reg-step4", 3, false);
                break;
            case 'step3':
                _disableFieldsExceptCurrentStep("div#reg-step1, div#reg-step2, div#reg-step4", 4, false);
                break;
        }
        _enableFieldsOfCurrentStep("div#reg-" + stepNo);
    }

    function _disableFieldsExceptCurrentStep(divIdCommaSeparated, stepNo, active) {
        $(divIdCommaSeparated).hide().find('input, textarea, button, select').attr('disabled', 'disabled');
        active === true ? $("ul.registration-step li:nth-child(" + stepNo + ")").addClass("active") : $("ul.registration-step li:nth-child(" + stepNo + ")").removeClass("active");
    }

    $('#business-register-form input').bind('keypress', function (event) {
        //event.preventDefault();
        if (event.keyCode === 13) {
            $("#business-register-form input[type='submit']").trigger('click');
        }
    });

    function _enableFieldsOfCurrentStep(divId) {
        $(divId).show().find('input, textarea, button, select').removeAttr('disabled', 'disabled')
    }

    $("div#syncWithBilling").find("select").on("change", function () {
        if ($('input[type=radio][name=same_address_info]:checked').val() == 'yes') {
            syncBillingShippingAddress();
        }
    });
    
    $("div#syncWithBilling").find("input, textarea").on("keyup", function () {
        if ($('input[type=radio][name=same_address_info]:checked').val() == 'yes') {
            syncBillingShippingAddress();
        }
    });


    function syncBillingShippingAddress() {
        $("#shipping_address_1").val($('#billing_address_1').val());
        $("#shipping_address_2").val($('#billing_address_2').val());
        $("select#shipping_country").val($('#billing_country').val());
        $('select#shipping_country').selectpicker('refresh');
        //$("select#shipping_state").val($('#billing_state').val());
        var billingStateId = $('#billing_state').val();
        if (billingStateId != "") {
            var billingStateText = $("select#billing_state option[value=" + billingStateId + "]").text();
            $('select#shipping_state').append('<option selected="selected" value=' + billingStateId + '>' + billingStateText + '</option>');
            $('select#shipping_state').selectpicker('refresh');
        }

        //$("select#shipping_city").val($('#billing_city').val());
        var billingCityId = $('#billing_city').val();
        if (billingCityId != "") {
            var billingCityText = $("select#billing_city option[value=" + billingCityId + "]").text();
            $('select#shipping_city').append('<option selected="selected" value=' + billingCityId + '>' + billingCityText + '</option>');
            $('select#shipping_city').selectpicker('refresh');
        }

        $("#shipping_postal_code").val($('#billing_postal_code').val());

        $("div.biz-shipping-info-disable-enable").find('input, textarea, button, select').attr('disabled', 'disabled');
    }

    /*on radio option change - copy address*/
    $('input[type=radio][name=same_address_info]').change(function () {
        if (this.value == 'yes') {
            syncBillingShippingAddress();
        } else if (this.value == 'no') {
            $("div.biz-shipping-info-disable-enable").find('input, textarea, button, select').removeAttr('disabled', 'disabled');
            $("#shipping_address_1").val("");
            $("#shipping_address_2").val("");
            $("#shipping_postal_code").val("");
            $("select#shipping_country").val("").selectpicker('refresh');
            $("select#shipping_state").empty().append('<option value="">Select State</option>').selectpicker('refresh');
            $("select#shipping_city").empty().append('<option value="">Select City</option>').selectpicker('refresh');
        }
    });

});