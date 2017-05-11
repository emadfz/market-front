<!-- BEGIN FORM-->
{!! Form::open(['route' => 'forumStore', 'class' => 'ajax','id'=>'postadv', 'files' => true ])!!}
<div class="row">
    <div class="col-sm-4">
        <div class="form-group {!!$errors->has('topic_department_id') ? 'has-error' : ''!!}">
            {!! Form::label('topic_department_id', trans('forum.common.topic_department_id'), ['class' => '']) !!}
            <div class="selectbox">
                {!! Form::select('topic_department_id', array('' => 'Select Category')+$departments, null , ['class' => 'selectpicker','id'=>'cat_id','tabindex' => '1']) !!}
                {!! $errors->first('topic_department_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group {!! $errors->has('topic_name') ? 'has-error' : '' !!}">
            {!! Form::label('title', trans("forum.common.topic_name"), ['class' => '']) !!}
            {!! Form::text('topic_name', null, ['class'=>'form-control','tabindex' => '2']) !!}
            {!! $errors->first('topic_name', '<span class="help-block">:message</span>') !!}
        </div>

    </div>
</div>


<div class="row">
    <div class="col-sm-12">
        
        <div class="form-group ckeditor-error {!! $errors->has('topic_description') ? 'has-error' : '' !!}">
            {!! Form::label('topic_description', trans("forum.common.topic_description"), ['class' => '']) !!}

            {!! Form::textarea('topic_description', null, ['class'=>'ckeditor','cols'=>'30','rows' => '5','tabindex' => '3']) !!}
            {!! $errors->first('topic_description', '<span class="help-block">:message</span>') !!}
        
        </div>
        
    </div>
</div>

<div class="clearfix text-right nomargin">

    <a class="cancel-link" href="{{route('forum')}}" title="Cancel">Cancel</a>
    {!! Form::submit(isset($model) ? trans("forum.common.post") : trans("forum.common.post"), ['class'=>'btn btn-primary','tabindex' => '4']) !!}

</div>
{!! Form::close() !!}
<!-- END FORM-->
@push('scripts')
<script src="http://cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script> 
<script>
CKEDITOR.replace("topic_description", {
    uiColor: "#F5F5F5",
    toolbar: "standard",
    toolbarLocation: 'bottom',
    scayt_autoStartup: false,
    enterMode: CKEDITOR.ENTER_BR,
    resize_enabled: false,
    disableNativeSpellChecker: false,
    htmlEncodeOutput: false,
	height: 120,
	removePlugins: 'elementspath',
    editingBlock: false,
    toolbarGroups:
            [
                {"name": "basicstyles", "groups": ["basicstyles", "cleanup"]},
                {"name": "links", "groups": ["links"]},
                {"name": "document", "groups": ["mode"]},
              
            ],
	// Remove the redundant buttons from toolbar groups defined above.
    removeButtons: 'Strike,Subscript,Source,RemoveFormat,Superscript,Anchor,Styles,Specialchar', toolbarLocation : 'bottom',
});
</script>
@endpush