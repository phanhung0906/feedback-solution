@extends('layout.layout')
@section('page')
        <div>
            <div class="form-inline missionForm" style="margin-top: 30px">
                <div class="form-group">
                    <input type="text" class="form-control textMission" placeholder="Add to project ...">
                </div>
                <button type="submit" class="btn btn-default addMission"><span class="fa fa-plus"></span> Create</button>
                <span class="chooseProject" style="display: none;">
                    <span style="font-size:18px;padding-left:3%;padding-right:3%">Or Choose Project:</span>
                    <div class="form-group">
                        <select class="form-control listMission" style="min-width:100px">

                        </select>
                    </div>
                </span>
                <button id="submit-all" class="btn btn-primary" style="margin-left:5%;display: none"><span class="fa fa-cloud-upload"> Upload all files</span></button>
            </div>
            <div class="formuploads">
                <div id="dropzone">
                    <form action="/upload" enctype="multipart/form-data" method="post" id="target" class="col-md-8">
                        <input id="uploader" type="file" name="file" class="hide file"> <br>

                        <div class="text-center dz-default dz-message">
                            <h1>Click or drag files here to upload ...</h1>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script type='text/javascript'>
            $(document).ready(function(){
                $('.addMission').click(function(){
                    $self = $(this);
                    $mission_name = $self.parent('.missionForm').find('.textMission').val();
                    for(var i=0; i< $mission_name.length; i++){
                        if ($mission_name.charAt(i) == '-'){
                            $.notify("Project's name must not have '-'");
                            return;
                        }
                    }
                    if( $mission_name != '' && $mission_name !=' '){
                        $('.opacity').show();
                        $.ajax({
                            type:'post',
                            url :'http://<?= ROOT_URL .'/addProject' ?>',
                            data:{
                                mission_name:$mission_name,
                                user:'<?= Session::get('user') ?>'
                            }
                        }).done(function(response){
                                $('.opacity').hide();
                                if(response == "OK"){
                                    var template = $('.option').html().replace(/#mission#/g,$mission_name);
                                    $self.parent('.missionForm').find('.listMission').append(template);
                                    var template2 = $('.missionMenu').html().replace(/#mission#/g,$mission_name)
                                        .replace(/#url#/g,'http://'+'<?= ROOT_URL.'/'.Session::get('user') ?>'+'/'+$mission_name);
                                    $('.menu').html('');
                                    $('.menu').append(template2);
                                    $($('.menu').html()).appendTo($('.showmenu').find('.project'));
                                    $('.chooseProject').show();
                                    $('#target').show();
                                    $.notify("Creat project successfully",'success');
                                }else{
                                    $.notify("There is same project's name.Please create another project's name");
                                }
                                $self.parent('.missionForm').find('.textMission').val('');
                            });
                    }
                });
                var myDropzone = new Dropzone("#target",{  parallelUploads: 100, autoProcessQueue: false });
                myDropzone.on("addedfile", function(file) {
                    $('#target').find('.dz-message').hide();
                    $("#submit-all").show();
                    //delete file
                    file.previewElement.children[0].children[4].addEventListener("click", function() {
                        myDropzone.removeFile(file);
                        if(myDropzone.getQueuedFiles().length == 0){
                            $('#target').find('.dz-message').show();
                            $("#submit-all").hide();
                        }
                    });
                });
                $("#submit-all").on("click", function() {
                    myDropzone.processQueue();
                });
                myDropzone.on("success", function(file, response) {
                    $project = $('.listMission').val();
                    window.location = "http://<?= ROOT_URL.'/'.Session::get('user') ?>/"+$project;
                    $('.opacity').hide().css({'cursor':'auto'});
                });
                myDropzone.on("error", function(file, response) {
                    $.notify("Uploas error", "error");
                });
                myDropzone.on("sending", function(file, xhr,formData) {
                    $('.opacity').show().css({'cursor':'progress'});
                    $('.maintain').find('.alertUploads').hide();
                    $('#target').find('.dz-message').hide();
                    $project = $('.listMission').val();
                    $size = file.size/(1024*1024);
                    formData.append("project", $project);
                    formData.append("name", file.name);
                    formData.append("size", $size.toFixed(1) );
                    $('#target').find('b').hide();
                });
                $height = screen.height-300;
                $('#target').css({'min-height': $height+'px'});
                $('#target').find('.dz-message').css({'padding-top': ($height-150)/2+'px'});

            })
        </script>
@endsection