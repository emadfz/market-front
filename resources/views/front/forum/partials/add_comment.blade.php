<div class="forum_comments" style="display:none">
<!-- BEGIN FORM-->
{!! Form::open(['route' => 'forumStoreComment', 'class' => 'ajax','id'=>'postadv', 'files' => true ])!!}
<div class="row">
    <div class="col-sm-12">
        <div class="form-group ckeditor-error {!! $errors->has('comment') ? 'has-error' : '' !!}">

            {{ Form::hidden('forum_id', $getTopicData['id'], array('id' => 'forum_id')) }}
            {{ Form::hidden('topic_department_id', $getTopicData['topic_department_id'], array('id' => 'topic_department_id')) }}
            {!! Form::textarea('comment', null, ['class'=>'ckeditor','cols'=>'30','rows' => '5','tabindex' => '1']) !!}
            {!! $errors->first('comment', '<span class="help-block">:message</span>') !!}

        </div>
    </div>
</div>
<div class="clearfix text-right nomargin">
    <a href="{{route('forum')}}" class="cancel-link" title="Cancel">Cancel</a>
    {!! Form::submit(isset($model) ? trans("forum.common.post") : trans("forum.common.post"), ['class'=>'btn btn-primary','tabindex' => '2']) !!}
</div>
{!! Form::close() !!}
<!-- END FORM-->
</div>
@push('scripts')
<script src="http://cdn.ckeditor.com/4.5.3/standard/ckeditor.js"></script> 
<script>
CKEDITOR.replace("comment", {
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