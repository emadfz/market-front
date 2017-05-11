{!! Form::model($getUserData,['route' => 'storePersonalInfo', 'class' => 'ajax','id'=>'postadv', 'files' => true, 'method' =>'patch' ])!!}
<div class="equal-column">
    <h4 class="bghead nomargin">Personal Information</h4>

    <!--Profile Image Upload Start-->
    <div class="borderbox clearfix">
        <div class="profile-personal clearfix">
            <div class="picupload-outer">
                <div class="circle-img">
                    <?php
                    $width = '';
                    $height = '';
                    if (empty($model->image)) {
                        if ($model->gender == 'Male') {
                            $image = env('APP_URL') . '/assets/front/img/upload/user-male.png';
                            $width = 111;
                            $height = 98;
                        } else if ($model->gender == 'Female') {
                            $image = env('APP_URL') . '/assets/front/img/upload/user-female.png';
                            $width = 111;
                            $height = 98;
                        }
                    } else {
                        $image = env('APP_URL') . '/assets/front/img/upload/' . $model->id . '/' . $model->image;
                    }
                    ?>
                    <img src="{{ $image }}" alt="image" width="{{$width}}" height="{{$height}}">
                    <span class="bgoverly"></span>
                </div>
                <div id="upload-demo"></div>
                <input type="file" id="upload">

                <a class="btn btn-primary btn-sm upload-result" id="uploadimage">Upload Image</a>
                <div id="upload-demo-i"></div>
                <button class="btn btn-danger btn-sm" id="removeimage">Remove</button>
            </div>
            <!--<div class="circle-img"> <img src="bootstrap/img/user-pic2.jpg" alt="UserPic" width="168" height="168"> <span class="bgoverly"></span> </div> -->

            <div class="personal-inforight">
                <div class="equal-column">
                    <div class="col-lg-5">
                        <div class="form-horizontal">
                            <div class="form-group">
                                {!! Form::label('title', trans("profile.label.userName"), ['class' => '']) !!}
                                <div class="profiledata">{{$model->username}}</div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('title', trans("profile.label.userId"), ['class' => '']) !!}
                                <div class="profiledata">{{$model->email}}<!--<a href="#changeemail" data-toggle="modal" title="Change Email">Change Email</a> --></div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('title', trans("profile.label.password"), ['class' => '']) !!}
                                <div class="profiledata">*******<a href="{{route('getPasswordPopup', [$model->id])}}" data-target="#change_password_ajax_modal_popup" data-toggle="modal" title="Change Password">Change Password</a></div>
                            </div>

                        </div>
                    </div>
                    <div class="col-lg-7 addprofile">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="title" class="control-label">{{ trans('profile.label.title') }}</label>
                                <div class="right">
                                    <div class="selectbox width125">
                                        {!! Form::select('title', $nameTitle, null, ['class'=>'selectpicker', 'id' => 'title']) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('title', trans("profile.label.first_name"), ['class' => 'control-label']) !!}
                                <div class="right">
                                    {!! Form::text('first_name', null, ['class'=>'form-control']) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('title', trans("profile.label.last_name"), ['class' => 'control-label']) !!}
                                <div class="right">
                                    {!! Form::text('last_name', null, ['class'=>'form-control']) !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="gender" class="control-label">{{ trans('profile.label.gender') }}</label>
                                <div class="right">
                                    <div class="selectbox width125">
                                        {!! Form::select('gender', $gender, null, ['class'=>'selectpicker', 'id' => 'gender']) !!}
                                    </div>
                                </div>
                            </div>

                            <?php
                            if (isset($model->phone_number)) {
                                $phone = explode(' ', $model->phone_number);

                                if (!isset($phone[1]))
                                    $country_code = '';
                                else
                                    $country_code = $phone[0];
                            }
                            ?>
                            <div class="form-group">
                                <label class="control-label" for="phone_number">{{ trans('profile.label.phone') }}</label>
                                <div class="right">
                                    <div class="outer-field">
                                        {!! Form::text('country_code', $country_code, ['class'=>'form-control width50 ccode', 'placeholder'=>'+11', 'id' => 'phone_number', 'maxlength'=>4]) !!}
                                        {!! Form::text('phone_number', @$phone[1], ['class'=>'form-control cphone-input', 'maxlength'=>16]) !!}
                                    </div>
                                </div>
                            </div>



                            <div class="form-group">
                                <label class="control-label" for="date_of_birth">{{ trans('profile.label.dateofbirth') }}</label>
                                <div class="right">
                                    <div class="outer-field dateouter">
                                        {!! Form::text('date_of_birth', null, ['class'=>'form-control datepicker-ui', 'maxDate'=>0, 'id' => 'date_of_birth']) !!}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <hr>
        <div class="form-btnblock clearfix text-right nomargin">

            <a class="cancel-link" href="{{route('getProfile')}}" title="Cancel">Cancel</a>
            {!! Form::submit(isset($model) ? trans("profile.label.save") : trans("profile.label.save"), ['class'=>'btn btn-primary','tabindex' => '4']) !!}

        </div>

    </div>
    <!--Profile Image Upload End--> 
</div>
{!! Form::close() !!}
@push('scripts')
<script>
    $(document).ready(function () {
        $("#date_of_birth").datepicker().datepicker("setDate", new Date('{{$model->date_of_birth}}'));

        $(".product-nav li .submenu li.active").each(function () {
            $(this).parent().addClass("openouter").parent("li").addClass("active");
        });
        $(".product-nav li").on("click", function () {
            $('.product-nav li > ul').not($(this).children("ul").toggle()).hide();
        })
        $("#removeimage").on("click", function () {
            $("#upload-demo-i").hide();
            $("#removeimage").hide();
            $("#uploadimage,.cr-slider-wrap").show();
        })
    });
    //
    $uploadCrop = $('#upload-demo').croppie({
        enableExif: true,
        viewport: {
            width: 176,
            height: 176,
            type: 'circle'
        },
        boundary: {
            width: 178,
            height: 178
        }
    });
    $('#upload').on('change', function () {
        $(".croppie-container").show();
        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function () {
                console.log('jQuery bind complete');
            });

        }
        reader.readAsDataURL(this.files[0]);
    });
    $('.upload-result').on('click', function (ev) {
        $uploadCrop.croppie('result', {
            type: 'canvas',
            size: 'viewport'
        }).then(function (resp) {

            $.ajax({
                url: "{{route('storeImage')}}",
                type: "POST",
                data: {"image": resp},
                success: function (data) {
                    html = '<img src="' + resp + '" />';
                    $("#upload-demo-i").html(html);
                    //$("#removeimage").show();
                    $("#uploadimage,.cr-slider-wrap").hide();
                    if (typeof data.redirectUrl !== 'undefined') {
                        window.location = data.redirectUrl;
                    }
                }
            });
        });
    });

</script>
@endpush