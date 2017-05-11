<div class="row">
    <div class="form-horizontal col-md-12 uploadbase-image">
        <div class="form-group">
            <label class="col-sm-2 control-label">Upload base images</label>
            <div class="col-sm-10">
                <div class="uploadbase-image" id="dropzone">    
                    <div class="row">
                        <div class="col-md-12">                                                        
                            <div class="dropzone dropzone-file-area" id="my-dropzone">
                                <h4 class="sbold" style="color:#800080">Drop files here or click to upload product images, Maximum Number of files upload at a time is 5, Product image upload is mandatory for getting to the next step</h4>
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

    $productImages = array();

    if(isset($productData->Files))
    {
        $productImages = $productData->Files;
        //dd($productImages);
    }
    if (!empty($productImages)) {
        foreach ($productImages as $key => $row) {
            
            $old_image  = $row->path;
            $moduleName = 'products';
            if( $row->file_type == 'image' )
            {
                $path       = public_path() . '/images/' . $moduleName . '/';
                $small      = $path . '/small/' . $old_image;
                
                if(file_exists($small))
                {
                    echo '<input type="hidden" name="image[]" value="' . $row->path . '"/>';
                    $images[$key]['path'] = getImageFullPath($row->path, 'products', 'small');
                    $images[$key]['name'] = $row->path;
                }
            }else if($row->file_type == 'video')
            {
                $path       = public_path() . '/images/' . $moduleName . '/';
                $small      = $path . '/video/' . $old_image;
                
                if(file_exists($small))
                {
                    echo '<input type="hidden" name="image[]" value="' . $row->path . '"/>';
                    $fullpath = getImageFullPath($row->path, 'products', 'video');
                    ?>
                    <video width="20" controls>
                      <source src="{{$fullpath}}" type="video/mp4">
                      
                      Your browser does not support HTML5 video.
                    </video>

                    <?php
                    
                    $images[$key]['path'] = $fullpath;
                    $images[$key]['name'] = $row->path;
                }
            }
        }
        $fileArray = json_encode(@$images, JSON_HEX_APOS);
    }
    ?>
</div>

@push('styles') 
<link href="{{ asset('assets/front/js/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">.dropzone{border: 1px solid #dedede !important;}</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/front/js/dropzone/dropzone.min.js') }}" type="text/javascript"></script>
<script>
Dropzone.autoDiscover = false;
var fileArray = '<?php echo (!empty($productImages)) ? $fileArray : ""; ?>';
var myDropzone = new Dropzone("#my-dropzone", {
    //autoProcessQueue: false,
    acceptedFiles: "image/*,video/*",
    maxFiles: 5, // Number of files at a time
    maxFilesize: 25, //in MB,
    url: '<?php echo route("uploadImage", [$productId]); ?>',
    addRemoveLinks: true,
    init: function () {
        this.on("sending", function (file, xhr, formData) {
            formData.append("module", "products");
            if ( $('#title').val().length <= 0 ) {
                that.removeFile(file);
            }
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
        location.reload();
    },
    removedfile: function(file) {
        x = confirm('Do you want to remove?');
        if(!x){
            return false;
        }else{
            $.ajax({
                url: '<?php echo route("removeProductImage", [$productId]); ?>', //your php file path to remove specified image
                type: "POST",
                datatype:'json',
                data: {
                    filenamenew: file.name,
                    type: 'delete',
                },
                success: function (response) {
                    if(response.status == 'success')
                    {
                        toastr.success('Product image remove successfully.');
                        location.reload();
                    }else{
                         toastr.error('There is some error, Please try again later');
                    }
                },
            });
        }
    },
});

$('#cropbox').cropper({
  aspectRatio: 1 / 1,
  resizable: false,
  guides: false,
  dragCrop: false,
  autoCropArea: 0.4,
  checkImageOrigin: false,
  preview: '.avatar'
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