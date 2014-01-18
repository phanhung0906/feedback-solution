@extends('layout.layout')
@section('page')
@include('user.header')
<div id='upload-content'>
    <?php $newCurrentProject = str_replace('-',' ',$currentProject); ?>
    <h3 style='text-align: center'>{{$newCurrentProject}}</h3>
    <div class="form-inline missionForm" style="margin-top: 30px;margin-bottom: 30px;">
        <h4><span class="fa fa-cloud-upload"></span> Upload New Image</h4>
    </div>
    <div class="formuploads">
            <form action="/image/upload" enctype="multipart/form-data" method="post" id="my-dropzone" class='dropzone' style='min-height: 360px;border:dashed 3px darkgray'>

            </form>
    </div>
    <button id="submit-all" class="btn btn-primary" style='margin-top: 20px;'><span class="fa fa-upload"> Upload all files</span></button>
</div>

<!-- temp -->
    <div class="option hide">
        <option>#mission#</option>
    </div>
    <div class="missionMenu hide">
        <li><a href="#url#" data-id="#mission#">#mission#</a></li>
    </div>

<script type='text/javascript'>
    $(document).ready(function(){
        //Dropzone
        var myDropzone = new Dropzone("#my-dropzone",{ parallelUploads: 100, autoProcessQueue: false, uploadMultiple: true });
        Dropzone.options.myDropzone = false;
        Dropzone.autoDiscover = false;

        myDropzone.on("addedfile", function(file) {
            $fileName = file.name;
            $fileLastName = $fileName.split('.');
            $num = $fileLastName.length-1;
            $('#target').find('.dz-message').hide();
            $("#submit-all").show();

            // Create the remove button
            var removeButton = Dropzone.createElement("<button class='btn btn-primary'>Remove file</button>");

            // Capture the Dropzone instance as closure.
            var _this = this;

            // Listen to the click event
            removeButton.addEventListener("click", function(e) {
                // Make sure the button click doesn't submit the form:
                e.preventDefault();
                e.stopPropagation();

                // Remove the file preview.
                _this.removeFile(file);
                // If you want to the delete the file on the server as well,
                // you can do the AJAX request here.
            });

            // Add the button to the file preview element.
            file.previewElement.appendChild(removeButton);

            //Delete file not an image
            if($fileLastName[$num].toLowerCase() == 'gif' || $fileLastName[$num].toLowerCase() == 'jpg' || $fileLastName[$num].toLowerCase() == 'png'){
                //Do nothing
            } else {
                $.notify("Uploas only file .png, .jpg and .gif", "error");
                file.previewElement.children[5].click();
            }
        });

        myDropzone.on("successmultiple", function(file, response) {
            $.notify("Uploads images successfully", "success");
            var delay = 2000;//2 seconds
            setTimeout(function(){
                $mission_name = '<?= $currentProject ?>';
                window.location = "http://<?= ROOT_URL . '/' . $session ?>/" + $mission_name + '/page/1';
            },delay);
        });

        myDropzone.on("maxfilesexceeded", function(file) {
            $('.navbar-fixed-top').notify("Maximum uploads is 10 file", {position:'bottom right'});
            this.removeFile(file);
        });
        myDropzone.on("sendingmultiple", function(file, xhr,formData) {
            <?php $newCurrentProject = str_replace('-',' ',$currentProject); ?>
            $project = '<?= $newCurrentProject ?>';
            formData.append("project", $project);
            var num = file.length;
            var size = [];
            for(var i = 0; i < num; i++ ){
                $size = (file[i].size/(1024*1024)).toFixed(1) + 'MB';
                if((file[i].size/(1024*1024)).toFixed(1) <= 0) $size = (file[i].size/1024).toFixed(1) + 'KB';
                size.push($size);
            }
            formData.append("size", size );
        });

        $('#submit-all').click(function() {
            if($('#my-dropzone').find('.dz-preview').length > 0) {
                        myDropzone.processQueue();
            } else {
                $('.navbar-fixed-top').notify('You need to add new image before submit',{position:'bottom right'});
            }
        });

        $height = screen.height - 600;
        $('#upload-content').css({'min-height': $height + 'px'});
    })
</script>
@endsection