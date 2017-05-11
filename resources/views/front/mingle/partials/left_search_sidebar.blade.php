
{!! Form::model($getUserData,['route' => 'searchMingle', 'class' => 'ajax mingle_search_form','id'=>'postadv', 'files' => true, 'method' =>'post' ])!!}
<a href="#" class="close-filter">Close</a>
<div class="searchouter">
    <div class="input-search">
        <input name="search" type="text" class="form-control padd-right35" placeholder="Search By Email / Username">
<!--        <a href="#" class="search-icon"></a> -->
    </div>
</div>

<div class="leftcol-outer">
    <h4>Search By Location</h4>
    <div class="selectbox">

        <div class="selectbox">
            {!! Form::select('country_id', (['' => 'Select Country']+ $countries),null,['class' => 'selectpicker select-country','id' => 'country', 'data-targetState'=>'select-state']) !!}
        </div>

    </div>
    <div class="selectbox">

        <div class="selectbox">
            @if(!empty($Id))
            {!! Form::select('state_id', getAllStates(@$getUserData['country_id'], TRUE),@$getUserData['state_id'],['class' => 'selectpicker select-state','id' => 'state', 'data-targetCity'=>'select-city']) !!}
            @else
            {!! Form::select('state_id', (['' => 'Select State']),null,['class' => 'selectpicker select-state','id' => 'state', 'data-targetCity'=>'select-city']) !!}
            @endif
        </div>

    </div>
    <div class="selectbox">

        <div class="selectbox">
            @if(!empty($Id))
            {!! Form::select('city_id', getAllCities(@$getUserData['state_id'], TRUE),@$getUserData['city_id'],['class' => 'selectpicker select-city','id'=>'city']) !!}
            @else
            {!! Form::select('city_id',(['' => 'Select City']),null,['class' => 'selectpicker select-city','id'=>'city']) !!}
            @endif
        </div>

    </div>
    <div class="custom-checkbox vertical mrg-bott10">
        <label for="selectphoto" class="right">
            <input type="checkbox" id="selectphoto" value="1" name="withphoto">
            <span></span>Select With Photo only</label>
    </div>
    <div class="mrg-bott10">

        <h4>Select By Industries</h4>
        {!! Form::select('industries[]', ($industries),null,['class' => 'selectpicker','id' => 'industries','multiple'=>'multiple','data-selected-text-format'=>'count > 3','data-actions-box'=>true]) !!}

    </div>
    
    <div class="mrg-bott10">
        <h4>Select By Interests</h4>
        {!! Form::select('hobbies[]', ($hobbies),null,['class' => 'selectpicker','id' => 'hobby','multiple'=>'multiple','data-selected-text-format'=>'count > 3','data-actions-box'=>true]) !!}
    </div>
    <div class="clearfix">
        <h4>Search By Age Group</h4>
        <select name="ages[]" class="selectpicker" multiple data-selected-text-format="count > 3" data-actions-box="true">
            <option value="1-25">Below 25</option>
            <option value="25-35">25 - 35</option>
            <option value="35-45">35 - 45</option>
            <option value="45-55">45 - 55</option>
            <option value="55-99">Above 55</option>
        </select>

        <!--        <a href="#" class="btn btn-primary btn-sm pull-right mrg-top10">search</a>-->
        <input type="button" title="Save" class="btn btn-primary btn-sm pull-right mrg-top10 submit_search_mingle" value="search">
    </div>
    <div class="mrg-bott10">
        <h4><a href="#" title="Zodiac Sign">Select By <span>Zodiac Sign</span></a></h4>
    </div>
</div>    

{!! Form::close() !!}


@push('scripts')
<script>

    $("body").delegate(".submit_search_mingle", "click", function (event, state) {

        var thisdata = $(this);
        var form = $(".mingle_search_form");
        $.ajax({
            url: '{{route("searchMingle")}}',
            type: 'POST',
            dataType: 'json',
            data: form.serialize(),
            success: function (response) {
                if (response.status == 'success' && response.gridview_html != '' && response.listview_html != '') {
                    $('#gridview').html(response.gridview_html);
                    $('#listview').html(response.listview_html);
                    $('.mingle_bartext').show();
                    $('#search_count_mingle').html(response.usersCount);
                    if ($('#mingle_count').html() > response.pageData)
                        $('#pageData').html(response.pageData);
                    else
                        $('#pageData').html($('#mingle_count').html());
                    mingle_page_limit = $('.mingle-selectpicker').val();
                } else if (response.status == 'success' && response.html == '') {
                    selfObj.remove();
                } else {
                    alert('Could not get the Data. Please contact Administrator!!');
                    return false;
                }
            },
            error: function (data) {
                if (data.status === 401) {
                    //window.location="{{URL::to('individual-register')}}";
                    $(".signinModal").click();
                }
                if (data.status === 422) {
                    toastr.error("{{ trans('message.failure') }}");
                }
            }
        });
    });

</script>
@endpush