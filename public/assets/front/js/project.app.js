/*
 * Globally defined for all ajax call to overcome from Token Mismatch error
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});

$(function () {
    /*
     * --------------------
     * Ajaxify those forms
     * --------------------
     * All forms with the 'ajax' class will automatically handle showing errors etc.
     */
    $('form.ajax').ajaxForm({
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

            //added for ajax call unauthorise error by vishal
            if (401 == data.status) {
                //window.location="{{URL::to('individual-register')}}";
                $(".signinModal").click();
            } else {
                // Form validation error.
                if (422 == data.status) {
                    processFormErrors($form, $.parseJSON(data.responseText));
                    return;
                }

                toastr.error('Whoops! It looks like something went wrong on servers.\n\Please try again, or contact support if the problem persists.');

                var $submitButton = $form.find('input[type=submit]');
                toggleSubmitDisabled($submitButton);

                $('.uploadProgress').hide();
            }
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

                    if ($form.hasClass('closeModalAfterReportFlag')) {
                        $('.modal, .modal-backdrop').hide();
                        $('.addreport').modal('hide');
                        $('.modal-backdrop').remove();

                        if (typeof (data.data) !== 'undefined' && data.data.type == 'comment') {
                            $('#flagcomment' + data.data.ref_id).addClass('active');
                            $('#flagcomment' + data.data.ref_id).find('a').attr('href', 'javascript::void(0)');
                            $('#flagcomment' + data.data.ref_id).find('a').attr('data-target', '');
                        } else if (typeof (data.data) !== 'undefined' && data.data.type == 'topic') {
                            $('#flag' + data.data.ref_id).addClass('active');
                            $('#flag' + data.data.ref_id).find('a').attr('href', 'javascript::void(0)');
                            $('#flag' + data.data.ref_id).find('a').attr('data-target', '');
                        }
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
                    if (typeof data.redirectUrl !== 'undefined' && data.redirectUrl == 'self') { 
                        window.location.reload();
                        return false;
                    }

                    if (typeof data.redirectUrl !== 'undefined') {
                        window.location = data.redirectUrl;
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
});

function showMessage(message, status) {
    if (status == 'success') {
        toastr.success(message);
    } else if (status == 'error') {
        toastr.error(message);
    } else {
        toastr.info(message)
    }
}

function processFormErrors($form, errors) {
    
    

    $.each(errors, function (index, error) {
        if ((index.indexOf(".") >= 0)) {
            var selector = '.' + index.replace(/\./g, "\\.");
            if (selector.indexOf("available_date") > 0 || selector.indexOf("from_time") > 0 || selector.indexOf("to_time") > 0)
            {
                $('.scheduled-field').find('div.classified_error_div').remove();
                $('.scheduled-field').append('<div class="help-block error classified_error_div"> The preview scheduled All field is required. </div>').closest("div").addClass('has-error');
            }

            if (selector.indexOf("product_files") > 0)
            {   
                $('#upload_classified_product_image').append('<div class="help-block error classified_error_div"> Please upload atleast one image. </div>').closest("div").addClass('has-error');
            }
            
            
            $('.categoryChange').find('div.error').remove();
            $(selector, $form).after('<div class="help-block error">' + error + '</div>').parent().addClass('has-error');    
            
            
            //$(selector, $form).next('div.help-block').remove();
        } else {

            var $input = $(':input[name=' + index + ']', $form);
            if (index == 'global_form_message') {
                toastr.error(error);
            } else if (index == 'report_value') {
                $('.error_forum_report').after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($input.prop('type') === 'file') {
                if(index == 'uploadvideo')
                {
                    $('#upload_classified_product_video').after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');    
                }else{
                    $('#input-' + $input.prop('name')).after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');    
                }
            } else if ($("textarea[name=" + index + "]").hasClass("ckeditor")) {
                // for textarea having class ckeditor
                $("textarea[name=" + index + "]").next().after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
            } else if ($("select[name=" + index + "]").hasClass("selectpicker")) {
                // for select box having class selectpicker
                $input.closest("div.btn-group.bootstrap-select").after('<div class="help-block error">' + error + '</div>').closest("div").parent().addClass('has-error');
            } else {
                if($input.selector ==  ':input[name=variant_attributes]')
                {
                    $('.variantAttrCheckUncheck').closest('label').next().next().after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');

                }
                if ($input.closest("form").hasClass("custom-wo-public")) {
                    $input.closest('.input-icon').after('<div class="help-block error">' + error + '</div>').parent().addClass('has-error');
                } else {
                    if ($input.prop('type') == 'checkbox') {
                        // for checkbox

                        $input.next().next().after('<div class="help-block error">' + error + '</div>').closest("div").addClass('has-error');
                        $('#checkbox-'+ $input.prop('name')).closest('label').after('<div class="help-block error">' + error + '</div>').closest("div.form-group").addClass('has-error');
                    } else {
                        $input.after('<div class="help-block error">' + error + '</div>').closest("div").addClass('has-error');
                        

                    }
                }
            }
        }
    });

    var $submitButton = $form.find('input[type=submit]');
    toggleSubmitDisabled($submitButton);
}

function toggleSubmitDisabled($submitButton) {
    if ($submitButton.hasClass('disabled')) {
        $submitButton.attr('disabled', false).removeClass('disabled').val($submitButton.data('original-text'));
        return;
    }
    $submitButton.data('original-text', $submitButton.val()).attr('disabled', true).addClass('disabled').val('Processing...');
}
/*
 * toastr setting
 */
toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": true,
    "positionClass": "toast-top-center", //"toast-top-full-width",
    "preventDuplicates": true,
    "onclick": null,
    "showDuration": "5000",
    "hideDuration": "2000",
    "timeOut": "5000", // How long the toast will display without user interaction
    "extendedTimeOut": "3000", // How long the toast will display after a user hovers over it
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
}
/*
 * Password strength generator https://github.com/ablanco/jquery.pwstrength.bootstrap
 */
var handlePasswordStrengthChecker = function () {
    var initialized = false;
    var input = $("#password_strength");

    input.keydown(function () {
        if (initialized === false) {
            // set base options
            input.pwstrength({
                ui: {
                    /*showErrors: true, */showProgressBar: true,
                    verdicts: ["Weak", "Normal", "Medium", "Strong", "Very Strong"],
                    raisePower: 3,
                    minChar: 8,
                    scores: [17, 26, 40, 50, 60]
                },
            });
            // add your own rule to calculate the password strength
            input.pwstrength("addRule", "demoRule", function (options, word, score) {
                return word.match(/[a-z].[0-9]/) && score;
            }, 10, true);
            // set as initialized 
            initialized = true;
        }
    });
}
handlePasswordStrengthChecker();

/*
 * Password view icon show/hide
 */
$(".password-view").click(function () {
    $(this).hasClass("pwd-just-clicked") ? $(this).removeClass("pwd-just-clicked").prevAll("input[type=text]").attr("type", "password") : $(this).addClass("pwd-just-clicked").prevAll("input[type=password]").attr("type", "text");
});

/*
 * checkbox link 
 */
$('.indi-termschecks label a, .termschecks label a').on("click", function (event, data) {
    event.stopPropagation();
    $(this).attr('href');
});

/*
 * Generate/Refresh captcha for signin and forgot password modal popup
 */
var signin_captcha_html;
var forgot_captcha_child_div_html;
var onloadCallback = function () {
    /* Renders the HTML element with id 'example1' as a reCAPTCHA widget. The id of the reCAPTCHA widget is assigned to respective widget div id.*/
    signin_captcha_html = grecaptcha.render('signin_captcha_html', {'sitekey': googleRecaptchaSiteKey, 'theme': 'light'});
    forgot_captcha_child_div_html = grecaptcha.render('forgot_captcha_child_div_html', {'sitekey': googleRecaptchaSiteKey, 'theme': 'light'});
};
$(".signinModal").click(function () {
    $("form#signinForm").find('.error.help-block').remove();
    $("form#signinForm").find('.has-error').removeClass('has-error');
    $("#signinModal").modal("show");
});
$(".forgotModal").click(function () {
    $("form#forgotPasswordForm").find('.error.help-block').remove();
    $("form#forgotPasswordForm").find('.has-error').removeClass('has-error');
    $("#forgotModal").modal("show");
    refreshGenerateCaptcha('forgot_captcha_parent_div_id', forgot_captcha_child_div_html);
});

/*--- Login Scripts ---*/
$(function () {
    $('form.login-ajax').ajaxForm({
        delegation: true,
        beforeSubmit: function (formData, jqForm, options) {
            $(jqForm[0]).find('.error.help-block').remove();
            $(jqForm[0]).find('.has-error').removeClass('has-error');
            var $submitButton = $(jqForm[0]).find('input[type=submit]');
            toggleSubmitDisabled($submitButton);
            if ($('input[name=captcha_hdn]').val() == 1) {/*signin_captcha_html used in project.app.js*/
                if (isRecapchaValid(signin_captcha_html) === false) {
                    processFormErrors($(jqForm), {'g-recaptcha-response': captchaValidationMessage});
                    return false;
                }
            }
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
        },
        success: function (data, statusText, xhr, $form) {
            switch (data.status) {
                case 'success':
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
                    break;
                case 'error':
                    if (data.loginFailedLimitExceed === true) {
                        refreshGenerateCaptcha('signin_captcha_failed_attempt', signin_captcha_html);
                        $("form#signinForm").find('input[name=captcha_hdn]').val(1);
                    } else {
                        $("form#signinForm").find('input[name=captcha_hdn]').val(0);
                    }
                    processFormErrors($form, data.messages);
                    break;
                default:
                    break;
            }
        },
        dataType: 'json'
    });

    /*--- Signin modal when stay sign in checked, remember should disabled and vice versa ---*/
    $("form#signinForm input[name=stay_sign_in]").on("change", function () {
        if ($(this).is(":checked")) {
            $("form#signinForm input[name=remember_password]").attr("disabled", true);
        } else {
            $("form#signinForm input[name=remember_password]").attr("disabled", false);
        }
    });
    $("form#signinForm input[name=remember_password]").on("change", function () {
        if ($(this).is(":checked")) {
            $("form#signinForm input[name=stay_sign_in]").attr("disabled", true);
        } else {
            $("form#signinForm input[name=stay_sign_in]").attr("disabled", false);
        }
    });
});

/*--- Forgot passowrd scripts ---*/
$(function () {
    $('form.forgot-password-ajax').ajaxForm({
        delegation: true,
        beforeSubmit: function (formData, jqForm, options) {
            $(jqForm[0]).find('.error.help-block').remove();
            $(jqForm[0]).find('.has-error').removeClass('has-error');
            var $submitButton = $(jqForm[0]).find('input[type=submit]');
            toggleSubmitDisabled($submitButton);
            if (isRecapchaValid(forgot_captcha_child_div_html) === false) {/*forgot_captcha_child_div_html used in project.app.js*/
                processFormErrors($(jqForm), {'g-recaptcha-response': captchaValidationMessage});
                return false;
            }
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
        },
        success: function (data, statusText, xhr, $form) {
            switch (data.status) {
                case 'success':
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
                    break;
                case 'error':
                    processFormErrors($form, data.messages);
                    break;
                default:
                    break;
            }
        },
        dataType: 'json'
    });
});
/*
 * Generate/Refresh captcha
 */
function refreshGenerateCaptcha(parentDivId, widget_id) {
    $("#" + parentDivId).show();
    grecaptcha.reset(widget_id);
    return true;
    /*$.ajax({
     url: refreshGenerateCaptchaUrl,
     type: 'get',
     dataType: 'html',
     success: function (json) {
     $("#"+parentDivId).show();
     $("#"+childDivId).html(json);
     },
     error: function (data) {
     toastr.error("Something went wrong");//alert('Try Again.');
     }
     });*/
}
function isRecapchaValid(widget_id) {
    var res = grecaptcha.getResponse(widget_id);
    return (res == "" || res == undefined || res.length == 0) ? false : true;
}



$("#signinUsername").on('blur', function () {
    $.ajax({
        url: upCkUrl,
        type: 'POST',
        dataType: 'json',
        data: {method: '_POST', username: $(this).val(), submit: true},
        success: function (r) {
            if (r.success == 1) {
                $("input[name=password]").val(Base64.decode(r.p));
                $("form#signinForm").find("span.password-view").removeClass("password-view");
            }
        },
        error: function (data) {
            if (data.status === 422) {
                toastr.error("{{ trans('message.failure') }}");
            }
        }
    });
});

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}



var Base64 = {
    _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    encode: function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output + this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) + this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
    decode: function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    _utf8_encode: function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    },
    _utf8_decode: function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}

/*--- prevent from cut copy paste event ---*/

$('body').delegate(".check_numeric, .sku_quantity_tr_cls , .digit_only_cls, input[name='product_listing_price']", "cut copy paste", function (e) {
    e.preventDefault();
    return false;
});


/* sku quantity - allow only number/digit */
$(document).on('keypress', '.sku_quantity_tr_cls, .digit_only_cls', function (event) {
    if (!checkNumericValue(event, false, true)) {
        event.preventDefault();
        return false; //stop character from entering input
    }
});


/* apply this common class to allow only numeric value with dot and minus sign */
$(document).on('keypress', '.check_numeric', function (event) {
    if (!checkNumericValue(event, true)) {
        event.preventDefault();
        return false; //stop character from entering input
    }
    /*if (event.which != 8 && isNaN(String.fromCharCode(event.which))) {event.preventDefault(); //stop character from entering input}*/
});

/* Common function to allow only numeric value with decimal point, minus */
function checkNumericValue(event, allow_minus, allow_digit_only) {
    var result;
    try {
        var charCode = (event.which) ? event.which : event.keyCode;

        if ((charCode >= 48 && charCode <= 57) || charCode == 110 || charCode == 8 || charCode == 46 || charCode == 45) {
            result = (allow_minus !== true && charCode == 45) ? false : true;
            result = (allow_digit_only === true && (charCode == 46 || charCode == 45)) ? false : true;
        }
    } catch (err) {
        result = false;
    }
    return result;
}
function addtocart(id,qty){
        $.ajax({
            url: url+'/cart',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
                $(".cartcontent").html(r['count']);
            },
            error: function (data) {
            }
        });
        toastr.success("Shopping Cart:  Item has been added to shopping cart.",'',{
  "closeButton": true,
  "debug": false,
  "positionClass": "toast-top-right",
  "onclick": null,
  "showDuration": "1000",
  "hideDuration": "1000",
  "timeOut": "1000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
});
}

function addtocartSku(id,qty){
        $.ajax({
            url: url+'/cartSku',
            type: 'POST',
            dataType: 'json',
            data: {method: '_POST', product_id: id, submit: true},
            success: function (r) {
                $(".cartcontent").html(r['count']);
            },
            error: function (data) {
            }
        });
        toastr.success("Shopping Cart:  Item has been added to shopping cart.",'',{
  "closeButton": true,
  "debug": false,
  "positionClass": "toast-top-right",
  "onclick": null,
  "showDuration": "1000",
  "hideDuration": "1000",
  "timeOut": "1000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
});
}