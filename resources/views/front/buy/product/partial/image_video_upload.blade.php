<div class="row">
    <div class="form-horizontal col-md-12 uploadbase-image">
        <div class="form-group">
            <label class="col-sm-2 control-label">Upload base images</label>
            <div class="col-sm-10">
                <div class="uploadbase-image" id="dropzone">    
                    <div class="row">
                        <div class="col-md-12">                                                        
                            <div class="dropzone dropzone-file-area" id="my-dropzone">
                                <h3 class="sbold">Drop files here or click to upload product images</h3>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>
        </div>
    </div>
</div>
<div id="image_container">
    <?php
    $productImages = $productData->Files;
    if (!empty($productImages)) {
        foreach ($productImages as $key => $row) {
            echo '<input type="hidden" name="image[]" value="' . $row->path . '"/>';
            $images[$key]['path'] = getImageFullPath($row->path, 'products', 'small');
            $images[$key]['name'] = $row->path;
        }
        $fileArray = json_encode(@$images, JSON_HEX_APOS);
    }
    ?>
</div>
<!--<div class="row">
    <div class="form-horizontal col-md-12 uploadbase-image">
        <div class="form-group">
            <label class="col-sm-2 control-label">Upload base images</label>
            <div class="col-sm-10">
                <div class="custom-radio">
                    <label for="baseimage1">
                        <input type="radio" name="baseimage"  checked="" id="baseimage1">
                        <span></span>
                        <div class="basebox"> <a class="close" href="#" title="Delete">Delete</a> <img src="{{asset('assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </div>
                    </label>
                    <label for="baseimage2">
                        <input type="radio" name="baseimage" id="baseimage2">
                        <span></span>
                        <div class="basebox"> <a class="close" href="#" title="Delete">Delete</a> <img src="{{asset('assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </div>
                    </label>
                    <label for="baseimage3">
                        <input type="radio" name="baseimage" id="baseimage3">
                        <span></span>
                        <div class="basebox"> <a class="close" href="#" title="Delete">Delete</a> <img src="{{asset('assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </div>
                    </label>
                    <label for="baseimage4">
                        <input type="radio" name="baseimage" id="baseimage4">
                        <span></span>
                        <div class="basebox"> <a class="close" href="#" title="Delete">Delete</a> <img src="{{asset('assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </div>
                    </label>
                    <label for="baseimage5">
                        <input type="radio" name="baseimage" id="baseimage5">
                        <span></span>
                        <div class="basebox"> <a class="close" href="#" title="Delete">Delete</a> <img src="{{asset('assets/front/img/xs-thumb.jpg')}}" width="46" height="46" alt=""> </div>
                    </label>
                </div>
                <span class="basebox addimage">
                    <input type="file">
                    <small class="value">Add image</small>
                </span>
                <span class="marktext">(Please select the radio button below the image to mark as default).</span>
            </div>
        </div>
        <div class="form-group">
            <label for="uploadvideo" class="col-sm-2 control-label">Upload video</label>
            <div class="col-md-6 col-sm-10">
                <div class="file">
                    <input type="file" name="ba_employee_csv" id="uploadvideo">
                    <span class="value"></span> <span class="btn btn-primary">Browse</span> </div>
            </div>
            <div class="col-sm-10 col-sm-offset-2">
                <span class="small-semibold">You can upload a video up to 25 megabytes (MB) in size or provide a link of video</span> </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-4">
                <label class="sr-only" for="urllink"></label>
                <input type="text" name="" id="urllink" class="form-control" placeholder="https://www.youtube.com/watch?v=cx83">
            </div>
        </div>
    </div>
</div>-->
@push('styles') 
<link href="{{ asset('assets/front/js/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">.dropzone{border: 1px solid #dedede !important;}</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/front/js/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
<script>
Dropzone.autoDiscover = false;
var fileArray = '<?php echo (isset($productId)) ? $fileArray : ""; ?>';
var myDropzone = new Dropzone("#my-dropzone", {
    //autoProcessQueue: false,
    acceptedFiles: "image/*",
    maxFiles: 5, // Number of files at a time
    maxFilesize: 2, //in MB,
    url: '<?php echo route("uploadImage", [$productId]); ?>',
    addRemoveLinks: true,
    init: function () {
        this.on("sending", function (file, xhr, formData) {
            formData.append("module", "products");
        });
    },
    headers: {
        'X-CSRF-Token': "<?php echo csrf_token(); ?>"
                //xhr.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}')
    },
    success: function (response, data, cal) {
        $('#image_container').append('<input type="hidden" name="image[]" value="' + data.path + '"/>')
    },
    complete: function (file) {
        var pre = this.previewsContainer;
        $(pre).find('.dz-preview').removeClass().addClass('dz-preview dz-processing dz-success dz-complete dz-image-preview');
    },
});


if (fileArray) {
    $(function () {
        fileArrayobject = JSON.parse(fileArray);
        $.each(fileArrayobject, function (index, value) {
            var mockFile = {name: value.name, size: 12345};
            myDropzone.options.addedfile.call(myDropzone, mockFile);
            myDropzone.options.thumbnail.call(myDropzone, mockFile, value.path);
        });
        $('.dz-preview').removeClass().addClass('dz-preview dz-processing dz-success dz-complete dz-image-preview');
    });
}
</script>
@endpush