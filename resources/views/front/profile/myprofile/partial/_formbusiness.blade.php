{!! Form::model($getUserData,['route' => 'storeBusinessInfo', 'class' => 'ajax','id'=>'postadv', 'files' => true, 'method' =>'post' ])!!}
<div class="equal-column">
    <h4 class="bghead">Business Information</h4>
    <!--Profile Business Information Start-->
    <div class="row">
        <div class="form-horizontal">
            <div class=" col-md-6">


                <div class="form-group">
                    <label for="business_name" class="control-label col-md-4">{{trans('form.auth.business_name')}}<span class="required">*</span></label>
                    <div class="col-md-8">
                        {!! Form::text('business_name', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_name', 'maxlength'=>50]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="industry_type_id" class="control-label col-md-4">{{trans('form.auth.industry_type')}}<span class="required">*</span></label>
                    <div class="col-md-8">
                        <div class="selectbox">
                            {!! Form::select('industry_type_id', $industryTypes, null, ['class'=>'selectpicker', 'id' => 'industry_type']) !!}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_details" class="control-label col-md-4">{{trans('form.auth.business_details')}}<span class="required">*</span></label>
                    <div class="col-md-8">
                        {!! Form::textarea('business_details', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_details', 'maxlength'=>500, 'rows'=>'4', 'cols'=>'50']) !!}
                    </div>
                </div>


                <div class="form-group">
                    <label for="position_id" class="control-label col-md-4">{{ trans('form.auth.position') }}<span class="required">*</span></label>
                    <div class="col-md-8">
                        <div class="selectbox mrg-bott10">
                            {!! Form::select('position_id', $position, null, ['class'=>'selectpicker', 'id' => 'position']) !!}
                        </div>
                        {!! Form::text('position_other', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'position_other', 'maxlength'=>50]) !!}
                    </div>
                </div>


            </div>
            <div class="col-md-6">

                <div class="form-group">
                    <label for="tax_id_number" class="control-label col-md-4">{{trans('form.auth.tax_id_no')}}<span class="required">*</span></label>
                    <div class="col-md-8">
                        {!! Form::text('tax_id_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'tax_id_number', 'maxlength'=>50]) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label for="business_reg_number" class="control-label col-md-4">{{trans('form.auth.business_reg_no')}}</label>
                    <div class="col-md-8">
                        {!! Form::text('business_reg_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_reg_number', 'maxlength'=>50]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="business_phone_number" class="control-label col-md-4">{{trans('form.auth.business_phone')}}</label>
                    <div class="col-md-8">
                        {!! Form::text('business_phone_number', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'business_phone_number', 'maxlength'=>20]) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="website" class="control-label col-md-4">{{trans('form.common.website')}}</label>
                    <div class="col-md-8">
                        {!! Form::text('website', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'website', 'maxlength'=>100]) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="mrg-topnone">
    <div class="row">
        <div class="form-horizontal store-upload">
            <h5 class="blacktitle">Store Information</h5>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('image', trans("profile.label.image"), ['class' => 'col-md-4 control-label']) !!}
                    <div class=" col-md-8">
                        <div class="store-imgblock">
                            @if(isset($getUserData->Files[0]) && !empty($getUserData->Files[0]))
                            <!--                                {!! getImageByPath($getUserData->Files[0]->path,'main') !!}-->
                            <img src="{{asset('images/main/'.$getUserData->Files[0]->path)}}" alt="" id="view_store_image">
                            <input type="hidden" value="{{$getUserData->Files[0]->path}}" name="old_image[0]">
                            <input type="hidden" value="{{$getUserData->Files[0]->id}}" name="file_id[0]">
                            <!--                                {!! Form::hidden('old_image', $getUserData->Files[0]->path, ['class'=>'form-control']) !!}-->
                            @else 
                            <img src="{{asset('assets/front/img/img-default.png')}}" alt="" id="store_image">
                            @endif
                        </div>
                        <span class="link">Change Image
                            <input name="file[0]" type="file" id="storeimage" class="uploadimageprod">
                            <!--                            {!! Form::file('image[]', null, ['class'=>'uploadimageprod','id'=>'storeimage']) !!}-->
                        </span>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('image', trans("profile.label.storevideo"), ['class' => 'col-md-4 control-label']) !!}
                    <div class=" col-md-8">
                        <div class="store-imgblock">
                            <!--                            <video id="store_video" height="150" width="284">
                                                            <source src="{{asset('assets/front/img/img-default.png')}}" alt="">
                                                        </video>-->
                            @if(isset($getUserData->Files[1]) && !empty($getUserData->Files[1]))

                            <video id="view_store_video" height="150" width="284" controls="">
                                <source src="{{asset('images/video/'.$getUserData->Files[1]->path)}}" alt="">
                            </video>
                            {!! Form::hidden('old_image[1]', $getUserData->Files[1]->path, ['class'=>'form-control']) !!}
                             <input type="hidden" value="{{$getUserData->Files[1]->id}}" name="file_id[1]">
                            @else 
                            <video id="store_video" src="{{asset('assets/front/img/img-default.png')}}" height="150" width="284"></video>
                            @endif
                        </div>
                        <span class="link">Change Video
                            <input name="file[1]" type="file" id="storevideo" class="uploadvideoprod">
                            <span>(max. 25Mb)</span></span>
                    </div>
                </div>
                <div class="or clearfix"><span>or</span></div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="sr-only" for="video_link"></label>
                        {!! Form::text('video_link', null, ['class'=>'form-control', 'placeholder'=>'', 'id' => 'video_link', 'placeholder'=>'https://www.youtube.com/watch?v=cx83']) !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <hr class="mrg-topnone">
    <div class="form-btnblock clearfix text-right nomargin">
        <a href="#" title="Cancel" class="cancel-link" data-dismiss="modal">Cancel</a>
        <input type="submit" title="Save" class="btn btn-primary" value="save">
    </div>
    <!--Profile Business Information End--> 
</div>
{!! Form::close() !!}
@push('style')
<style>
    //css 
    .store-imgblock-1 {
        background: #e5e5e5 none no-repeat scroll center center / cover ;
        display: inline-block;
        min-height: 215px;
        position: relative;
        vertical-align: top;
        width: 32%;
    }

</style>
@endpush
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        
        <?php if($getUserData->position_id == 5){ ?>
            $('#position_other').show();
        <?php }else{ ?>
            $('#position_other').hide();
        <?php } ?>
    });
    $(document).on('change', '#position', function (e) {
        if($(this).val() == 5){
            $('#position_other').show();
        }else{
            $('#position_other').hide();
        }
    });
    $(document).on('change', '.uploadimageprod', function (e) {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader)
            return; // no file selected, or no FileReader support

        if (/^image/.test(files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function () { // set image data as background of div
                console.log($(this));
                if (typeof $('#view_store_image').attr('id') !== 'undefined')
                    $('#view_store_image').attr("src", this.result);
                else
                    $('#store_image').attr("src", this.result);
                
            }
        }
    });
    $(document).on('change', '.uploadvideoprod', function (e) {
        var files = !!this.files ? this.files : [];
        if (!files.length || !window.FileReader)
            return; // no file selected, or no FileReader support

        if (/^video/.test(files[0].type)) { // only image file
            var reader = new FileReader(); // instance of the FileReader
            reader.readAsDataURL(files[0]); // read the local file

            reader.onloadend = function () { // set image data as background of div
                console.log($(this));
                if (typeof $('#view_store_video').attr('id') !== 'undefined')
                    $('#view_store_video').attr("src", this.result);
                else
                    $('#store_video').attr("src", this.result);
            }
        }
    });
</script>
@endpush