@extends('layout.layout')
@section('page')
@include('project.header')
<ol class="breadcrumb">
    <li class="active"><b>Dasbboard</b></li>
</ol>
<div class="page">
    <div class="maintain">
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
        <div class="confirm1 hide">
            <button type="button" class="btn btn-danger delete" data-dismiss="modal" data-id="#id#">Delete</button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
        </div>
        <!-- /Delete Mission -->

        <div class='container home'>
            <div>
                <div class="alert alert-warning alertEditProject" style="display: none;">
                    Warning! You can't change this Project's name
                </div>
            </div>
            <div class="container text-center">
                <ul class='list-unstyled missionImg' style='display:inline-block'>
                    @foreach($projectImg as $projectImg)
                        <li class='pull-left'>
                            <a href="http://<?= ROOT_URL.'/'. $user.'/'. $projectImg['mission'].'/page/1' ?>" class="picture">
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

        <div class="missiontemp hide">
            <li class='pull-left'>
                <a href="#link#" class="picture">
                    <img src="http://<?= IMAGES_URL ?>#url#" class="img img-thumbnail"/>
                </a>
                <div class="missionName" style="overflow: hidden;height:43px;cursor:default;" title="Click to change name project">#mission#</div>
                <span class="numImg">Images <span class="badge">#numImg#</span></span>
                @if($user == $session)
                        <a class="deleteMission pull-right" data-toggle="modal" href="#modalDeleteMission" data-id="#id#"><span class="fa fa-trash-o"></span></a>
                @endif
            </li>
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
                            if(name.charAt(i) == '-'){
                                $.notify("Project's name have character '-'");
                                return;
                            }
                        }
                        if (name == '') {
                            $.notify("Project's name must not empty");
                            return;
                        }
                        if (name == ' ') {
                            $.notify("Project's name must not space");
                            return;
                        }
                        if (name == 'page') {
                            $.notify("Project's name must not 'page'");
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

            $('.missionImg').on("click",".deleteMission",function(){
                $self = $(this);
                $id   = $self.attr("data-id");
                $name = $self.parent('.pull-left').find('.missionName').html();
                $('#modalDeleteMission').find('.modal-body').html('').append('<p>Are you sure want to delete project <b>' + $name +'</b></p>');
                var template = $('.confirm1').html().replace(/#id#/g,$id);
                $('#modalDeleteMission').find('.confirm').html('');
                $(template).appendTo('.confirm');
                //Modal Delete Mission
                $('#modalDeleteMission').on('click','.delete',function(){
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
       <?php endif; ?>
    })
</script>
@endsection