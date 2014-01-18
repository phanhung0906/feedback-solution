@extends('layout.layout')
@section('page')
@include('project.header')
<ol class="breadcrumb">
    <li class="active"><b>Dasbboard</b></li>
</ol>
<div class="page">
    <div class="maintain">
        <!-- Delete project -->
           <!-- Modal -->
                <div class="modal fade" id="modalDeleteMission" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title"><span class="fa fa-trash-o"></span> Warning</h4>
                            </div>
                            <div class="modal-body">

                            </div>
                            <div class="modal-footer confirm">
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
        <!-- /Delete project -->

        <!-- Add new project -->
            <!-- Modal -->
                <div class="modal fade" id="addProject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title" id="myModalLabel"><span class="fa fa-folder-open"></span> Add new Project</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Project name</label>
                                    <input type="email" class="form-control" id="missionName">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary create">Create</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
        <!-- /Add new project -->

        <div class="confirm1 hide">
            <button type="button" class="btn btn-danger delete" data-dismiss="modal" data-id="#id#">Delete</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>


        <div class='container home'>
            <div>
                <div class="alert alert-warning alertEditProject" style="display: none;">
                    Warning! You can't change this Project's name
                </div>
            </div>
            <div class="container text-center">
                <ul class='list-unstyled missionImg'>
                    @if($user == $session)
                        <li class='pull-left'>
                            <a data-toggle="modal" data-target="#addProject" class="picture" style='background: url(http://<?= IMAGES_URL . '/picture/add-projects.png' ?>) transparent no-repeat center 50px; text-align: center;
                                text-decoration: none;color: black;font-weight: bold;padding-top: 172px;cursor: pointer;'>
                                Add new Project
                            </a>
                        </li>
                    @endif
                    @foreach($projectImg as $projectImg)
                        <li class='pull-left'>
                                <a href="http://<?= ROOT_URL.'/'. $user.'/'. $projectImg['mission'].'/page/1' ?>" class="picture" style='width:162px;line-height:162px;text-align: center;display: block'>
                                    <img src="http://<?= IMAGES_URL . $projectImg['img'] ?>" class="img img-thumbnail"/>
                                </a>
                            <div class="missionName" style="overflow: hidden;height:43px;cursor:default;" title="Click to change name project">{{$projectImg['mission']}}</div>
                            <span class="numImg">Images <span class="badge">{{$projectImg['num_img']}}</span></span>
                            @if($user == $session)
                                  <a class="deleteMission pull-right" data-toggle="modal" href="#modalDeleteMission" data-id={{$projectImg['id']}}><span class="fa fa-trash-o"></span></a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="container text-center">
                <ul class="pagination missionPage">
                    @if($num_page > 1)
                        @for($i=1 ;$i<$num_page+1;$i++)
                           <li <?php if($page == $i) echo "class='active'" ?>> <a href="http://<?= ROOT_URL . '/' . $user . '/page/' .$i ?>">{{$i}}</a></li>
                        @endfor
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        <?php if( $session == $user): ?>
            // Edit name of Project
            $('.missionImg').on('click','.missionName',function(){
                $self = $(this);
                var value = $self.html();
                var array = value.split('(');
                value = array[0];
                $('.missionImg').find('.missionName').show();
                $self.hide();
                var currentProjectId = $self.parent('li').find('.deleteMission').attr('data-id');
                $('.changename').val(value).data('currentProjectId',currentProjectId).insertAfter($self.prev()).show().focus();
            });

            $('.missionImg').on('keyup','.changename', function(e) {
                $self = $(this);
                switch (e.keyCode){
                    case 27:
                        $self.hide();
                        $('.missionImg').find('.missionName').show();
                        break;
                    case 13:
                        var name = $(this).val();
                        for(var i = 0; i< name.length ; i++){
                             if(name.charAt(i) == '-' || name.charAt(i)== '/' ||
                                 name.charAt(i) == '`' || name.charAt(i)== '~' ||
                                 name.charAt(i)== '@' || name.charAt(i)== '.' ||
                                 name.charAt(i) == '#' || name.charAt(i) == '?' ||
                                 name.charAt(i) == '%' || name.charAt(i) == '+' ||
                                 name.charAt(i) == '&' || name.charAt(i) == '(' ||
                                 name.charAt(i) == '|' || name.charAt(i) == '\\' ||
                                 name.charAt(i) == '\"' || name.charAt(i) == '\'' ||
                                 name.charAt(i) == '' || name.charAt(i) == ';' ||
                                 name.charAt(i) == '<' || name.charAt(i) == '>' ||
                                 name.charAt(i) == '[' || name.charAt(i) == ']' ||
                                 name.charAt(i) == '{' || name.charAt(i) == '}'
                                ) {
                                $.notify("Project's name can only consist of alphabetical, number, underscore and some character like ! ^ ) = * $ ");
                                return;
                            }
                        }
                        if (name.charAt(name.length - 1) == ' ') {
                            $('.navbar-fixed-top').notify("Last character can't be a space",{position:'bottom right'});
                            return;
                        }
                        if (name == '') {
                            $('.navbar-fixed-top').notify("Project's name must not empty",{position:'bottom right'});
                            return;
                        }
                        if (name == ' ') {
                            $('.navbar-fixed-top').notify("Project's name must not space",{position:'bottom right'});
                            return;
                        }
                        if (name == 'page') {
                            $('.navbar-fixed-top').notify("Project's name must not 'page'",{position:'bottom right'});
                            return;
                        }
                        $('.opacity').show();
                        $.ajax({
                            type:'post',
                            url: '/project/edit',
                            data: {
                                id : $self.data('currentProjectId'),
                                mission_name : name,
                                user : "<?= $user ?>"
                            }
                        }).done(function(response){
                                $('.opacity').hide();
                                if (response != false) {
                                    $self.parent('li').find('.missionName').html('').show().html(response);
                                    $url = "<?= 'http://'.ROOT_URL .'/'. $user . '/' ?>" + response+'/page/1';
                                    $self.prev().attr('href',$url);
                                    $self.hide().appendTo('.divchangename');
                                }
                                if (response == false){
                                    $('.home').find('.alertEditProject').show().delay(2000).fadeOut(1);
                                    $('.missionImg').find('.missionName').show();
                                    $self.hide().appendTo('.divchangename');
                                }
                            });
                        break;
                    default :
                        break;
                }
            });

            $('.deleteMission').click(function() {
                $self = $(this);
                $id   = $self.attr("data-id");
                $name = $self.parent('.pull-left').find('.missionName').html();
                $('#modalDeleteMission').find('.modal-body').html('').append('<p>Are you sure want to delete project <b>' + $name +'</b></p>');
                var template = $('.confirm1').html().replace(/#id#/g,$id);
                $('#modalDeleteMission').find('.confirm').html('');
                $(template).appendTo('.confirm');
                //Modal Delete Mission
                $('.delete').click(function() {
                    $('.opacity').show();
                    $self1 = $(this);
                    $id = $self1.data("id");
                    $.ajax({
                        type:"post",
                        url : '/project/delete',
                        data:{
                            id : $id
                        }
                    }).done(function(response){
                            $('.opacity').hide();
                            if(response ){
                                $.notify('Delete successfully','success');
                                $self.parent('.pull-left').remove();
                            }else{
                                $.notify('Delete was not successfully','error');
                            }
                        });
                });
            });

        $('.create').click(function(){
            $self = $(this);
            $mission_name = $('#addProject').find('#missionName').val();
            for (var i = 0; i < $mission_name.length; i++) {
                if($mission_name.charAt(i) == '-' || $mission_name.charAt(i)== '/' ||
                    $mission_name.charAt(i) == '`' || $mission_name.charAt(i)== '~' ||
                    $mission_name.charAt(i)== '@' || $mission_name.charAt(i)== '.' ||
                    $mission_name.charAt(i) == '#' || $mission_name.charAt(i) == '?' ||
                    $mission_name.charAt(i) == '%' || $mission_name.charAt(i) == '+' ||
                    $mission_name.charAt(i) == '&' || $mission_name.charAt(i) == '(' ||
                    $mission_name.charAt(i) == '|' || $mission_name.charAt(i) == '\\' ||
                    $mission_name.charAt(i) == '\"' || $mission_name.charAt(i) == '\'' ||
                    $mission_name.charAt(i) == '' || $mission_name.charAt(i) == ';' ||
                    $mission_name.charAt(i) == '<' || $mission_name.charAt(i) == '>' ||
                    $mission_name.charAt(i) == '[' || $mission_name.charAt(i) == ']' ||
                    $mission_name.charAt(i) == '{' || $mission_name.charAt(i) == '}'
                    ) {
                    $.notify("Project's name can only consist of alphabetical, number, underscore and some character like ! ^ ) = * $ ");
                    return;
                }
            }
            if ($mission_name == 'page') {
                $.notify("Project's name must not have name like 'page'");
                return;
            }
            if( $mission_name != '' && $mission_name != ' ') {
                $('#addProject').find('.close').click();
                $('.opacity').show();
                $.ajax({
                    type:'post',
                    url :'/project/add',
                    data:{
                        missionName  : $mission_name,
                        user         : '<?= $session ?>'
                    }
                }).done(function(response){
                        $('.opacity').hide();
                        if(response){
                            $.notify("Create project successfully", "success");
                            var delay = 1000;//2 seconds
                            setTimeout(function(){
                                $new_mission_name = $mission_name.replace(' ','-');
                                window.location = "http://<?= ROOT_URL . '/setting/upload/' ?>" + $new_mission_name;
                            },delay);
                        } else {
                            $.notify("There is project's name like '" + $mission_name + "' .Please create another project's name",'error');
                        }
                    });
            }else {
                $.notify("Project's name must not empty", 'error');
            }
        });
       <?php endif; ?>
    })
</script>
@endsection