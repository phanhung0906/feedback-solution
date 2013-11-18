@extends('layout.layout')
@section('page')
<?php $get = $_GET['project_name'];
$newget = str_replace("-"," ",$get);
?>
<ol class="breadcrumb">
    <li><a href="http://<?= ROOT_URL.'/'.$_GET['user'] ?>">Dasbboard</a></li>
    <li class="active"><b ><?= $newget ?></b></li>
</ol>
<div class="page">

<div class="maintain">

    <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><span class="glyphicon glyphicon-trash"></span> Warning</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer confirm">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    <div class="confirm1 hide">
        <button type="button" class="btn btn-danger delete" data-dismiss="modal" data-id="#id#">Delete</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
    </div>

    <div class="project">
        <div  style="margin-bottom: 20px;">
            <div class="alert alert-warning alertEditImg" style="display: none;">
                Warning! You can't change this name
            </div>
        </div>
        <div class='container'>
            <div class="container text-center">
                <ul class='list-unstyled projectImg' style='display: inline-block'>

                </ul>
            </div>

            <div class="container text-center">
                <ul class="pagination projectPage">

                </ul>
            </div>
        </div>
    </div>

    <div class="projecttemp hide">
        <li class='pull-left'>
            <a href="#link#" class="picture">
                <img src="http://<?= IMAGES_URL ?>#url#" class="img img-thumbnail" "/>
            </a>
            <div class="name" style="height:43px;overflow: hidden;cursor: default" title="Click to change name project">#name#</div>

            <?php if(Session::has('user') && isset($_GET['user'])): ?>
                <?php if(Session::get('user') == ($_GET['user'])): ?>
                    <a class="deleteImg pull-right" data-toggle="modal" href="#modalDelete" data-id="#id#" style="padding-left: 2px"><span class="fa fa-trash-o"></span></a>
                <?php endif; ?>
            <?php endif; ?>
            <span>Size: #size#  <span class="badge pull-right">#numcomment#</span></span>
        </li>
    </div>
</div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            <?php
               $get = $_GET['project_name'];
               $newget = str_replace("-"," ",$get);
            ?>
            function listpicture(user,mission,page){
                $PERPAGE = 15;
                $.ajax({
                    type: 'post',
                    url : 'http://<?= ROOT_URL ?>/listImage',
                    dataType: 'json',
                    data:{
                        user : user,
                        mission : mission
                    }
                }).done(function(response){
                        var num = response.result.length
                        var current_page = parseInt(page);
                        if(num%$PERPAGE == 0 ){
                            var num_link_page = parseInt(num/$PERPAGE);
                        }else
                            var num_link_page = parseInt(num/$PERPAGE) +1;
                        for(var j=1 ; j <= num_link_page; j++){
                            var start_page = (j-1)*$PERPAGE+1;
                            if ( num > j*$PERPAGE){
                                var end_page = j*$PERPAGE;
                            }else {
                                end_page = num;
                            }
                            var template3 = $('.missionPageTemp').html().replace(/#numpage#/g,j)
                                .replace(/#page#/g,j);
                            $('<li/>').html(template3).on('click',function(){
                                $('.projectPage').html('');
                                $('.projectImg').html('');
                                $page = $(this).find('a').attr('data-page');
                                listpicture("<?= $_GET['user'] ?>",'<?= $newget ?>',$page);
                            }).appendTo('.projectPage');

                            if ( j != current_page ) {
                                continue;
                            }
                            $('.projectPage').find("[data-page='" + j + "']").parent('li').addClass('active');
                            $('.projectImg').html('');
                            for (var k = start_page-1; k < end_page; k++) {
                                var template = $('.projecttemp').html().replace(/#url#/g, response.result[k].url_square)
                                    .replace(/#id#/g,response.result[k].id_pro)
                                    .replace(/#name#/g,response.result[k].name)
                                    .replace(/#size#/g,response.result[k].size+' Mb')
                                    .replace(/#numcomment#/g,response.cmt[k].numcomment)
                                    .replace(/#link#/g,'http://'+'<?= ROOT_URL .'/'. $_GET['user'] ?>'+'/'+ response.result[k].mission_name+'/'+ response.result[k].name);
                                $(template).appendTo('.projectImg');
                            }
                        }
                        if( j==2 ) $('.projectPage').html('');
                        var collaborators = response.collaborators[0];
                        switch(collaborators){
                            case 'public':
                                $('.project').find('.setting').html('').append("<span class='glyphicon glyphicon-globe'></span> Setting");
                                break;
                            case 'private':
                                $('.project').find('.setting').html('').append("<span class='glyphicon glyphicon-lock'></span> Setting");
                                break;
                            default :
                                $('.project').find('.setting').html('').append("<span class='glyphicon glyphicon-user'></span> Setting");
                                break;
                        }
                    });
            }

            listpicture("<?= $_GET['user'] ?>",'<?= $newget ?>',1);

            // Edit name of Image in project_name
            $('.projectImg').on('click','.name',function(){
                $self = $(this);
                var value = $self.html();
                var array = value.split('(');
                value = array[0];
                $('.projectImg').find('.name').show();
                $self.hide();
                var currentImgId = $self.parent('.pull-left').find('.deleteImg').attr('data-id');
                $('.changename').val(value).data('currentImgId',currentImgId).insertAfter($self.prev()).show().focus();
            });

            $('.project').on('keyup','.changename', function(e) {
                $self = $(this);
                switch (e.keyCode) {
                    // Esc
                    case 27:
                        $self.hide();
                        $('.projectImg').find('.name').show();
                        break;

                    // Enter
                    case 13:
                        var name = $(this).val();
                        for(var i= 0; i<name.length ; i++){
                            if(name.charAt(i)== '-'){
                                $.notify("Image 's name have character '-'");
                                return 'false';
                            }
                        }
                        if (name == '') {
                            return;
                        }
                        if (name == ' ') {
                            return;
                        }
                        $('.opacity').show();
                        $.ajax({
                            type: 'POST',
                            url: 'http://<?= ROOT_URL ?>/editImage',
                            data: {
                                id_pro: $self.data('currentImgId'),
                                name: name
                            }
                        }).done(function(response){ console.log(response);
                                $('.opacity').hide();
                                if (response != 'false') {
                                    $self.parent('li').find('.name').html('').show().html(response);
                                    $self.hide().appendTo('.divchangename');
                                    <?php if(isset($_GET['user']) && isset($_GET['project_name'])): ?>
                                    <?php
                                        $get = $_GET['project_name'];
                                        $newget = str_replace("-"," ",$get);
                                    ?>
                                    listpicture("<?= $_GET['user'] ?>",'<?= $newget ?>',1);
                                    <?php endif; ?>
                                }else {
                                    $('.alertEditImg').show().delay(2000).fadeOut(1);
                                    $('.projectImg').find('.name').show();
                                    $self.hide().appendTo('.divchangename');
                                }
                            });
                        break;
                    default:
                        // Do nothing
                        break;
                }
            });

            //Delete Image in project page
            $('.projectImg').on("click",".deleteImg",function(){
                $self= $(this);
                $id = $self.attr("data-id");
                $name = $self.parent('.pull-left').find('.name').html();
                $('#modalDelete').find('.modal-body').html('').append('<p>Are you sure want to delete image <b>' + $name +'</b></p>');
                var template = $('.confirm1').html().replace(/#id#/g,$id);
                $('#modalDelete').find('.confirm').html('');
                $(template).appendTo('.confirm');

                //Modal delete image in project page
                $('#modalDelete').on('click','.delete',function(){
                    $('.opacity').show();
                    $self1 = $(this);
                    $id = $self1.data("id");
                    $.ajax({
                        type:"post",
                        url : 'http://<?= ROOT_URL ?>/deleteImage',
                        data:{
                            id : $id
                        }
                    }).done(function(response){
                            $('.opacity').hide();
                            if(response == "OK"){
                                $.notify('Delete successfully','success');
                                $self.parent('.pull-left').remove();
                            }
                        });
                });
            });
            $('.changename').tooltip('show');
        });
    </script>
@endsection