<script>
    /*--Variables--*/
    var refreshGenerateCaptchaUrl = "{{route('refereshCaptcha')}}";
    var googleRecaptchaSiteKey = '{{env("NOCAPTCHA_SITEKEY")}}';
    var captchaValidationMessage = '{{trans("validation.captcha")}}';
    var upCkUrl = '{{route("getUserPassword")}}';    
    var assetsPath="{{ asset('') }}";
    var url="{{ URL('') }}";
</script>
@yield('occ_script')
<!-- jquery.min and bootstrap.min is available here. You can add other minified file here...-->
<script src="{{ asset('assets/front/js/lib.min.js') }}"></script>
<!-- Custom javascript code goes in general.js file... -->

<script src="{{ asset('assets/front/js/jquery.form.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery-validation/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/jquery-validation/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/bootstrap-pwstrength/pwstrength-bootstrap.min.js') }}" type="text/javascript"></script>

<script src="{{ asset('assets/front/js/productdetail.slideshow.js') }}"></script>
<script src="{{ asset('assets/front/js/general.js') }}"></script>
<script src="{{ asset('assets/front/js/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/front/js/project.app.js') }}"></script>
<script src="{{ asset('assets/front/js/jquery.blockui.min.js') }}"></script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
<script>
    /*
     * Select country change
     */
    $("body").delegate('.select-country', 'change', function (e) {
//$('.select-country').on('change', function (e) {
        var country_id = e.target.value;
        var targetStateCls = $(this).attr("data-targetState");
        var targetStateId = $('select.' + targetStateCls).attr('id');
        $.get("{{ url('information') }}/create/ajax-state?country_id=" + country_id, function (data) {
            $('select#' + targetStateId).empty().append('<option value="">Select State</option>');
            $.each(data, function (index, obj) {
                $('select#' + targetStateId).append('<option value=' + obj.id + '>' + obj.state_name + '</option>');
            });
            $('select#' + targetStateId).selectpicker('refresh');
            $('select#' + targetStateId).trigger("change");
        });
    });
    /*
     * Select state change
     */
    $("body").delegate('.select-state', 'change', function (e) {
//$('.select-state').on('change', function (e) {
        var state_id = e.target.value;
        var targetCityCls = $(this).attr("data-targetCity");
        var targetCityId = $('select.' + targetCityCls).attr('id');
        $.get("{{ url('information') }}/create/ajax-city?state_id=" + state_id, function (data) {
            $('select#' + targetCityId).empty().append('<option value="">Select City</option>');
            $.each(data, function (index, obj) {
                $('select#' + targetCityId).append('<option value="' + obj.id + '">' + obj.city_name + '</option>');
            });
            $('select#' + targetCityId).selectpicker('refresh');
        });
    });
</script>