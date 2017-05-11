<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group parent_div" id="catblock" style="margin-bottom: 10px;">
            <label for="category_id" class="control-label col-md-2">Select suitable category<span class="required">*</span></label>
            <div class="selectbox col-md-4">
                <div class="cssselect">
                    {!! Form::select('category_id[]', array('' => 'Select category')+$allCategories, (@$updateFlag == TRUE)?$productData['category_id']:null , ['class' => 'form-control parent categoryChange category_id.0','id'=>'category_id', 'autofocus'=>'autofocus']) !!}
                </div>
            </div>
        </div>
        <div id="show_sub_categories"></div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/front/js/livequery/jquery.livequery.js') }}" type="text/javascript"></script>
<script type="text/javascript">
var ddcount = 0;
        var categoryId = {!!json_encode($categoryIds)!!};
$(document).ready(function () {
    $('.parent, .childSelect').livequery('change', function () {
        $(this).closest('.parent_div').nextAll('.parent_div').remove();

        if (this.id == 'category_id') {
            $(this).closest('.parent_div').nextAll('#show_sub_categories').children().remove();
        }

        var categoryIds = "";
        $("select.categoryChange").each(function (key, value) {
            categoryIds = categoryIds + $(this).val() + ",";
        });
        categoryIds = categoryIds.slice(0, -1);

        $.ajax({url: '{{ route("getdynamicchilddropdown") }}', type: 'GET', data: {category_id: $(this).val(), selected_category_ids: categoryIds},
            success: function (response) {
                response = $.parseJSON(response);
                if (response.category_dropdown != "") {
                    finishProductAjax('show_sub_categories', escape(response.category_dropdown));
                }

                $("#productCondition").html(response.product_conditions);
                $("select[name=product_condition_id]").selectpicker('refresh');
            }, complete: function () {
                ddcount++;
                setTimeout(function () {
                    if (categoryId.length > ddcount) {
                        $('.categoryChange:eq(' + ddcount + ')').val(categoryId[ddcount]);
                        $('.categoryChange:eq(' + ddcount + ')').trigger('change');
                    }
                }, 100);
            },
        }, 'json');

        return false;
    });

    $('.categoryChange:eq(0)').val(categoryId[0]).trigger('change');
});

function finishProductAjax(id, response) {
    $('#loader').remove();
    $('#' + id).append(unescape(response));
}
</script>
@endpush