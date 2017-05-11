<h5 class="blacktitle">Meta Information  (Please describe the keyword or description the product, this will allow user to search your products with this tags and description)</h5>
<div class="row">
    <div class="form-horizontal col-md-12">
        <div class="form-group">
            <label class="control-label col-md-2">Meta title</label>
            <div class="col-md-10">
                {!! Form::text('meta_title', (@$updateFlag == TRUE)?$productData['meta_title']:null, ['class'=>'form-control', 'maxlength'=>50, 'placeholder'=>'Meta title']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">Meta keywords</label>
            <div class="col-md-10">
                {!! Form::textarea('meta_keywords', (@$updateFlag == TRUE)?$productData['meta_keywords']:null, ['class'=>'form-control', 'rows' => 3, 'maxlength'=>200, 'placeholder'=>'Meta keywords']) !!}
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-2">Meta description</label>
            <div class="col-md-10">
                {!! Form::textarea('meta_description', (@$updateFlag == TRUE)?$productData['meta_description']:null, ['class'=>'form-control', 'rows' => 2, 'maxlength'=>150, 'placeholder'=>'Meta description']) !!}
            </div>
        </div>
    </div>
</div>