@extends('layout.layout')
@include('images.header')
@section('page')
<ol class="breadcrumb">
    <li><a href="http://<?= ROOT_URL ?>">Dasbboard</a></li>
    <li class="active"><b >{{$newget}}</b></li>
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
                    @for($i=0; $i< count($image);$i++)
                        <li class='pull-left'>
                            <a href="http://<?= ROOT_URL . '/' . $user . '/' . $image[$i]->mission_name . '/' . $image[$i]->name ?>" class="picture">
                                <img src="http://<?= IMAGES_URL . $image[$i]->url_square ?>" class="img img-thumbnail" "/>
                            </a>
                            <div class="name" style="height:43px;overflow: hidden;cursor: default" title="Click to change name project">{{$image[$i]->name}}</div>
                                    @if($user == $session)
                                         <a class="deleteImg pull-right" data-toggle="modal" href="#modalDelete" data-id="<?= $image[$i]->id_pro ?>" style="padding-left: 2px"><span class="fa fa-trash-o"></span></a>
                                    @endif
                            <span>Size:  {{$image[$i]->size}}  <span class="badge pull-right">{{$numcmt[$i]->numcomment}}</span></span>
                        </li>
                    @endfor
                </ul>
            </div>

            <div class="container text-center">
                <ul class="pagination projectPage">
                    @if($num_page > 1)
                        @for($j=1; $j< ($num_page+1); $j++)
                             <li <?php if($page == $j) echo "class='active'" ?>><a href="http://<?= ROOT_URL . '/' . $user . '/' . $newget . '/page/' . $j ?>">{{$j}}</a></li>
                        @endfor
                    @endif
                </ul>
            </div>
        </div>
    </div>

</div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if($user == $session): ?>

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
                                    $.notify("Image's name have character '-'");
                                    return;
                                }
                                if(name.charAt(i)== '/'){
                                    $.notify("Image's name have character '/'");
                                    return;
                                }
                            }
                            if (name == '') {
                                $.notify("Image's name must not empty");
                                return;
                            }
                            if (name == ' ') {
                                $.notify("Project's name must not space");
                                return;
                            }
                            $('.opacity').show();
                            $.ajax({
                                type: 'POST',
                                url: 'http://<?= ROOT_URL ?>/editImage',
                                data: {
                                    id_pro: $self.data('currentImgId'),
                                    name: name,
                                    user:"<?= $user ?>",
                                    mission: "<?= $newget ?>"
                                }
                            }).done(function(response){
                                    $('.opacity').hide();
                                    if (response != false) {
                                        $self.parent('li').find('.name').html('').show().html(response);
                                        $url = "<?= 'http://' . ROOT_URL . '/' . $user . '/' . $get . '/' ?>"+response;
                                        $self.prev().attr('href',$url);
                                        $self.hide().appendTo('.divchangename');
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
                                $('.projectImg').find('.changename').hide().appendTo('.divchangename');
                                if(response){
                                    $.notify('Delete successfully','success');
                                    $self.parent('.pull-left').remove();
                                }
                            });
                    });
                });
                $('.changename').tooltip('show');
            <?php endif; ?>
            });
    </script>
@endsection