<!-- BEGIN FORM-->
{!! Form::open(['route' => 'forumReportFlag', 'class' => 'closeModalAfterReportFlag ajax','id'=>'postadv', 'files' => true ])!!}
<!-- Modal Add report Start-->
<!--<div class="modal" id="addreport" tabindex="-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content clearfix">-->
            <a href="#" class="close" data-dismiss="modal">{{trans('forum.common.close')}}</a>
            <div class="row">
                <div class="col-md-12">
                    <div class="modal-title">{{trans('forum.common.why_reporting')}}</div>
                    {!! Form::hidden('report_type', @$type, ['class' => 'report_type','id'=>'report_type']) !!}    
                    {!! Form::hidden('ref_id', @$ref_id, ['class' => 'ref_id','id'=>'ref_id']) !!}   
                    <div class="form-group required">
                        <div class="vertical custom-checkbox">
                          
                            @foreach (config('project.report_flag') as $key=>$val)
                            <label>{!! Form::checkbox("report_value[$key]", $val,null,array('class'=>'atleastone','data-attr-report'=>'atleastone')) !!}<span></span>{{$val}}</label>
                            @endforeach
                            
                        </div>
                        <div class="error_forum_report"></div>
                    </div>
                    <div class="form-group required">
                        <label>{{trans('forum.common.something_else')}}</label>
                        {!! Form::text('something_else', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'forgot_email']) !!}
                    </div>
                    <hr>	
                    <div class="clearfix text-right">
                        <a href="#" class="cancel-link" title="Cancel" data-dismiss="modal">Cancel</a>
                        <input type="submit" title="Submit" class="btn btn-primary reportflagsubmit" value="Submit">
                    </div>   

                </div>
            </div>
<!--        </div>
    </div>
</div>-->
{!! Form::close() !!}
<!-- END FORM-->
<!--Modal Add report Close-->  