@extends('layout.layout')
@section('page')
@include('design.header')
<div class="page">
    <div class="maintain">
        <!-- Show Image design -->
            <div class="image" style="height: 100%">
                <div class="onepic" style="padding-bottom: 10px;margin-top:30px;text-align: center;height:100%">
                    <div class="oneimg" style="position: relative;display: inline-block;">

                    </div>
                </div>
            </div>
        <!-- /Show Image design -->

        <!-- Show Comment design -->
            <div class="divchangecmt hide">
                <textarea class="form-control changecmt" style="display: none;" data-placement="bottom" title="hit enter to post reply or Esc to exit" placeholder="Enter name..." ></textarea>
            </div>
            <div class="onepicture hide" style="text-align:center; ">
                <img src="http://<?= IMAGES_URL ?>#url#" id='move' style="max-width:100%;margin: 0 auto;box-shadow: 2px 2px 20px black" data-id="#id#">
                <span class="tick btn note" style='display: none;background: url(http://<?= IMAGES_URL ?>/picture/note1.png) no-repeat 0 0;' ></span>
                <div class='box'></div>
                <div class="comment" style="display: none;box-shadow: 2px 2px 20px ">
                    <div style="background: #ffffff;border-top-left-radius: 8px;border-top-right-radius: 8px;">
                        <div style="border-bottom:1px solid #e0e0e0; padding:5px;margin-bottom: 2px;"><span class="glyphicon glyphicon-user"></span>Comment
                           @if($user == $session)
                                    <div class="pull-right showdeletebtn" style="color:red;cursor:pointer;font-size:12px">
                                        <span class="glyphicon glyphicon-remove-circle"></span>
                                    </div>
                                    <div class="pull-right deletebtn" style="color:red;cursor: pointer;font-size: 12px;display:none;">
                                        <span class="glyphicon glyphicon-remove-circle"></span>
                                        Delete thread
                                    </div>
                            @endif
                        </div>
                        <div class="showcmt" style="overflow-y: scroll;min-height: 150px;max-height:340px;width:300px;">

                        </div>
                    </div>
                    <textarea class="form-control cmt" placeholder="Write a comment ..." ></textarea>
                    <button class="btn btn-block btn-primary submit">Post this commnet</button>
                </div>
            </div>
        <!-- /Show Comment design -->

        <!-- template -->
            <div class="whocmt hide">
                <div style="border-radius: 10px;width:100%;" data-id="#id#" data-user="#author#" class="divcmt">
                    <div style="background: #eeeeee;padding:5px 0 5px 13px ;" ><b>-#author#</b> : <i style="font-size: 12px;">#time#</i>
                        <span class="glyphicon glyphicon-remove-circle pull-right deletecmt" style="margin-left: 5px;display: none;"> </span>
                        <span class="glyphicon glyphicon-edit pull-right editcmt" style="display: none;"> </span>
                    </div>
                    <div style="background:#F6F5F5;padding:5px 0 10px 13px;" class="contentcmt"> <span>#content#</span></div>
                </div>
            </div>
            <div class="btncmt" style="display:none;">
                <span class="tickcmt note"  data-id="#id#" style="background: url(http://<?= IMAGES_URL ?>/picture/note1.png) no-repeat 0 0;"></span>
            </div>
        <!-- /template -->
    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.allpage').css({'overflow':'hidden'});
            $('body').css({'background':'#292929'});
            $(".oneimg").hover(
                function() {
                    $('.tickcmt').show();
                    $( ".comment").hover(
                        function() {
                            $('.tickcmt').hide();
                            $('.tick').hide();
                        }, function() {
                            $('.tickcmt').show();
                            $('.tick').hide();
                        }
                    );
                }, function() {
                    $('.tickcmt').hide();
                    $('.tick').hide();
                    $( ".comment").hide();
                }
            );

            function picture(user,mission,name) {
                $.ajax({
                    type: 'post',
                    url : '/design/list',
                    dataType: 'json',
                    data:{
                        user    : user,
                        mission : mission,
                        name    : name
                    }
                }).done(function(response) {
                        $('.oneimg').html('');
                        $('.breadImg').html('');
                        $('.breadImg').append("<b >"+response.result[0].name+"</b>");
                        var template = $('.onepicture').html().replace(/#url#/g, response.result[0].content)
                            .replace(/#id#/g,response.result[0].id_pro);
                        $(template).appendTo('.oneimg');
                        var num =  response.btn.length;
                        for(var i = 0; i< num; i++){
                            var template1 = $('.btncmt').html().replace(/#id#/g,response.btn[i].id_btn);
                            var y= parseInt(response.btn[i].y);
                            var x= parseInt(response.btn[i].x);
                            $(template1).appendTo('.oneimg').css({'top' : y ,'left' : x, 'position':'absolute'});
                        }
                    });
            }

            picture('<?= $user ?>', '<?= $newget ?>', '<?= $newget2 ?>');

        // Change icon on mouse
            $('.tool').click(function() {
                action = $(this).attr('data-action');
                switch (action) {
                    case 'move':
                        $('.oneimg ').css({'cursor':'move'});
                        $( ".oneimg" ).draggable({ cursor: "move" });
                        $( ".oneimg" ).draggable('enable');
                        break;
                    case 'comment':
                        $('.oneimg ').css({'cursor':'default'});
                        $( ".oneimg" ).draggable({ cursor: "move" });
                        $( ".oneimg" ).draggable('destroy');
                        break;
                }
            });

            function nl2br (str, is_xhtml) {
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            }

            ox = 0; oy = 0;
            $( ".oneimg").on('mousemove','img',function(e) {
                if($('.action').find('.active').attr('data-action') == 'comment') {
                    $(this).css({'cursor':'url(https://redpen.io/assets/cursor-add-annotation.png),auto'});
                }
                if($('.action').find('.active').attr('data-action') == 'move') {
                    $(this).css({'cursor':'move'});
                }
                var x =''; var y ='';
                x = e.pageX;
                y = e.pageY;
                var offset = $(this).offset();
                var top = y - offset.top ; var left = x- offset.left;
                $( ".oneimg").find('img').click(function(e) {
                    if($('.action').find('.active').attr('data-action') == 'comment') {
                        $('.changecmt').hide().appendTo('.divchangecmt');
                        $('.tick').css({'top' : top, 'left' : left, 'position' : 'absolute'}).show();
                        $('.comment').hide();
                    }
                });
            });

            var id_submit;
            $('.oneimg').on('click','.tick',function(e) {
                id_submit = 0;
                var x =''; var y ='';
                x = e.pageX;
                y = e.pageY;
                var offset = $('.oneimg').find('#move').offset();
                oy = y - offset.top ;  ox = x - offset.left;
                $self = $(this);
                $('.comment').find('.showcmt').html('');
                $('.comment').css({'top' : oy, 'left' : ox + 23, 'position' : 'absolute'}).show('blind',300);
            });

            $('.oneimg').on('click', '.tickcmt', function() {
                $('.oneimg').find('.showdeletebtn').show().end().find('.deletebtn').hide();
                $('.comment').hide();
                $('.tick').hide();
                $('.changecmt').hide().appendTo('.divchangecmt');
                $self = $(this);
                var y = ''; var x = '';
                $id_pro = $('.image').find('#move').attr('data-id');
                $id_btn = $self.attr('data-id');  id_submit = $id_btn;
                $.ajax({
                    type : "post",
                    url  : '/comment/list',
                    dataType : 'json',
                    data:{
                        id_pro : $id_pro,
                        id_btn : $id_btn
                    }
                }).done(function(response){
                        $('.showcmt').html('');
                        var num = response.result.length;
                        for (var i=0 ; i< num ; i++) {
                            var template = $('.whocmt').html().replace(/#author#/g,response.result[i].user)
                                .replace(/#content#/g,nl2br(response.result[i].comment))
                                .replace(/#id#/g,response.result[i].id)
                                .replace(/#time#/g,response.result[i].time);
                            $(template).appendTo('.showcmt');
                            <?php if($session == $user): ?>
                                $('.showcmt').find('.deletecmt').show();
                            <?php endif; ?>
                        }
                        $('.showcmt').find("[data-user='" + "<?= $session ?>" + "']").find('.editcmt').show();
                        y = response.result[0].y;
                        x = response.result[0].x;
                        oy = y; ox = x;
                        $('.comment').css({'top':parseInt(y) , 'left':parseInt(x)+23,'position':'absolute' }).show('blind',300);
                    });
            });

            $('.oneimg').on('click', '.deletecmt', function() {
                $self1= $(this);
                $('.changecmt').hide().appendTo('.divchangecmt');
                $id = $self1.parents('.divcmt').attr('data-id');
                $.ajax({
                    type:"post",
                    url : '/comment/delete',
                    data:{
                        id : $id
                    }
                }).done(function(success){
                        if(success){
                        $self1.parents('.divcmt').remove();
                        $.ajax({
                            type:"post",
                            url : '/button/check',
                            data:{
                                id_btn : id_submit
                            }
                        }).done(function(response) {
                                if(response){
                                    $('.onepic').find("[data-id='" + id_submit + "']").remove();
                                    $('.comment').hide();
                                }
                            });
                        }
                    });
            });

            $('.oneimg').on('click','.showdeletebtn',function() {
                $(this).hide();
                $(this).next().show();
            });

            $('.oneimg').on('click','.deletebtn',function() {
                $.ajax({
                    type:"post",
                    url :'/button/delete',
                    data:{
                        id_btn : id_submit
                    }
                }).done(function(response) {
                        if(response){
                            $('.onepic').find("[data-id='" + id_submit + "']").remove();
                            $('.comment').hide();
                        }
                    });
            });

            $('.onepic').on('click','.editcmt',function(){
                $self = $(this);
                $comment =   $self.parent('div').next();
                var value = $self.parent('div').next().find('span').html(); console.log(value);
                var commentId = $self.parents('.divcmt').attr('data-id');
                $('.divcmt').find('.contentcmt').show();
                $self.parent('div').next().hide();
                var new_value = value.replace(/<br>/g,"");console.log(new_value);
                $('.changecmt').val(new_value).data('commentId', commentId).insertAfter($comment).show().focus();
            });

            function getCaret(el) {
                if (el.selectionStart) {
                    return el.selectionStart;
                } else if (document.selection) {
                    el.focus();
                    var r = document.selection.createRange();
                    if (r == null) {
                        return 0;
                    }
                    var re = el.createTextRange(),
                        rc = re.duplicate();
                    re.moveToBookmark(r.getBookmark());
                    rc.setEndPoint('EndToStart', re);
                    return rc.text.length;
                }
                return 0;
            }
            $('.changecmt').on('keyup', function(e) {
                $self = $(this);
                if (e.keyCode == 13 && e.shiftKey) {
                    var content = this.value;
                    var caret = getCaret(this);
                    this.value = content.substring(0,caret) + content.substring(caret,content.length);
                    event.stopPropagation();
                    return;
                }
                switch (e.keyCode) {
                    // Esc
                    case 27:
                        $self.hide();
                        $('.divcmt').find('.contentcmt').show();
                        break;

                    // Enter
                    case 13:
                        var new_comment = $(this).val();
                        if ( new_comment == '') {
                            return;
                        }
                        if ( new_comment == ' ') {
                            return ;
                        }
                        var id = $self.parent('.divcmt').attr('data-id');
                        $.ajax({
                            type:"post",
                            url : '/comment/edit',
                            data:{
                                id : id,
                                new_comment : new_comment
                            }
                        }).done(function(response){
                                if( response ){
                                    $self.prev().find('span').html('').html(nl2br(new_comment)).end().show();
                                    $self.hide().appendTo('.divchangecmt');
                                }
                            });
                        break;
                    default:
                        // Do nothing
                        break;
                }
            });

            $('.oneimg').on('click','.submit',function(){
                $self   = $(this);
                $user   = '<?= $session ?>';
                $id_pro = $('.image').find('#move').attr('data-id');
                $cmt    = $self.parent('.comment').find('.cmt').val();
                if( $cmt == ' ' || $cmt == '') {
                    return false;
                } else {
                    $.ajax({
                        type:"post",
                        url : '/comment/add',
                        data:{
                            user   : $user,
                            id_pro : $id_pro,
                            x      : ox,
                            y      : oy,
                            cmt    : $cmt
                        }
                    }).done(function(response){
                            a= new Date();
                            var time =  a.getFullYear() +'-'+ (a.getMonth()+1) +"-"+ a.getDate()+ ' ' + a.getHours() +':'+ a.getMinutes()+':'+ a.getSeconds();
                            var template = $('.whocmt').html().replace(/#author#/g,$user)
                                .replace(/#content#/g,nl2br($cmt))
                                .replace(/#id#/g,response)
                                .replace(/#time#/g,time);
                            $(template).appendTo('.showcmt');
                            $('.showcmt').find("[data-user='"+"<?= $session ?>"+"']").find('.editcmt').show();
                            <?php if($session == $user): ?>
                               $('.showcmt').find(".divcmt").find('.deletecmt').show();
                            <?php endif; ?>
                            $('.tick').hide();
                            $('.cmt').val('');
                            if(id_submit ==0){
                                $('.comment').hide();
                                picture('<?= $user ?>', '<?= $newget ?>', '<?= $newget2 ?>');
                            }
                        });
                }
            });
            $('.changecmt').tooltip('show');
        });
    </script>
@endsection