<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group flex-wrap">
            <label class="control-label col-md-2">Description<span class="required">*</span></label>
            <div class="col-md-10">
                {!! Form::textarea('ckeditor', (@$updateFlag == TRUE)?$productData['description']:null, ['class'=>'ckeditor', 'rows' => 5, 'cols' => 30]) !!}
            </div>
        </div>
    </div>
</div>