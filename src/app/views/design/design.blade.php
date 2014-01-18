@extends('layout.layout')
@section('page')
@include('design.header')
<div class="page">
    <div class="maintain">
        <!-- Show Image design -->
            <div class="image">
                <div class="onepic">

                    <div id="wPaint" class="oneimg">
                        <div id="revision_image_wrapper">
                            <img class="revision_image" style='position: absolute;max-width: 100%'>
                        </div>
                        <span class="tick btn note" style='background: url(http://<?= IMAGES_URL ?>/picture/note1.png) no-repeat 0 0;' ></span>
                        <div class="comment">
                            <div style="background: #ffffff;border-top-left-radius: 8px;border-top-right-radius: 8px;border-bottom: 1px solid #e0e0e0;">
                                <div style="border-bottom:1px solid #e0e0e0; padding:5px;margin-bottom: 2px;"><span class="glyphicon glyphicon-user"></span>Comment
                                    @if($user == $session)
                                        <div class="pull-right showdeletebtn">
                                            <span class="glyphicon glyphicon-remove-circle" style="cursor: pointer;"></span>
                                        </div>
                                        <div class="pull-right deletebtn">
                                            <span class="glyphicon glyphicon-remove-circle" style="cursor: pointer;"></span>
                                            Delete thread
                                        </div>
                                    @endif
                                </div>
                                <div class="showcmt">

                                </div>
                            </div>
                            <textarea class="form-control cmt" placeholder="Write a comment ..." ></textarea>
                            <button class="btn btn-block btn-primary submit">Post this commnet</button>
                        </div>
                    </div>
                    <div class="oneimg1">
                        <img src="http://<?= IMAGES_URL . $image['result'][0]->content ?>" id='move' data-id="{{$image['result'][0]->id_pro}}">
                    </div>
                </div>
            </div>
        <!-- /Show Image design -->

        <!-- Show Comment design -->
            <div class="divchangecmt hide">
                <textarea class="form-control changecmt" style="display: none;" data-placement="bottom" title="hit enter to post reply or Esc to exit" placeholder="Enter name..." ></textarea>
            </div>

        <!-- /Show Comment design -->

        <!-- template -->
            <div class="whocmt hide">
                <div data-id="#id#" data-user="#author#" class="divcmt">
                    <div class='divcmt_child1'><b>-#author#</b>
<!--                        : <i style='font-size: 12px'>#time#</i>-->
                        <span class="glyphicon glyphicon-remove-circle pull-right deletecmt"> </span>
                        <span class="glyphicon glyphicon-edit pull-right editcmt"> </span>
                    </div>
                    <div class="contentcmt"><span>#content#</span></div>
                </div>
            </div>
            <div class="btncmt" style="display:none;">
                <span class="tickcmt note"  data-id="#id#" data-show="#show#" style="background: url(http://<?= IMAGES_URL ?>/picture/note1.png) no-repeat 0 0;"></span>
            </div>
        <!-- /template -->

    </div>
</div>
    <script type="text/javascript">
        $(document).ready(function() {
            var id_submit;
            $('body').css({'background':'#292929'});
            $("#wPaint").hover(
                function() {
//                    $( ".comment").hide();
                    $('.tickcmt').fadeIn(100);
                }, function() {
                    $('.tickcmt').hide();
//                    $('.tick').hide();
//                    $( ".comment").hide();
                    $('html').click(function(){
                        $( ".comment").hide();
                        $('.tick').hide();
                        $('.tickcmt').hide();
                    });
                    if ($('.comment').find('.cmt').is(":focus")) {
                        $('#wPaint').find("[data-id='"+ id_submit +"']").show();
                    } else {
                        $( ".comment").hide();
                        $('.tick').hide();
                    }
                }
            );
//            $(".comment").mouseleave(function() {
//                        $(this).hide();
//            })
            function button(user,mission,name) {
                $.ajax({
                    type: 'post',
                    url : '/button/list',
                    dataType: 'json',
                    data:{
                        user    : user,
                        mission : mission,
                        name    : name
                    }
                }).done(function(response) {
                        $('.oneimg').find('.tick').find("[data-show = 'show']").remove();
                        var num =  response.btn.length;
                        for(var i = 0; i< num; i++){
                            var template1 = $('.btncmt').html().replace(/#id#/g,response.btn[i].id_btn)
                                                                .replace(/#show#/g,'show');
                            var y = parseInt(response.btn[i].y);
                            var x = parseInt(response.btn[i].x);
                            $(template1).appendTo('.oneimg').css({'top' : y ,'left' : x, 'position':'absolute'});
                        }
                    });
            }
            button('<?= $user ?>','<?= $newget ?>','<?= $newget2 ?>');

        // Change icon on mouse
            $('.tool').click(function() {
                $('.wPaint-menu-icon-name-strokeStyle').find('.wColorPicker').find('.wColorPicker-palettes-holder').find('.wColorPicker-palette-color').first().click();
                action = $(this).attr('data-action');
                switch (action) {
                    case 'comment':
                        $('#wPaint').find('.wColorPicker-theme-classic ').hide();
                        $('#wPaint').find('.wPaint-menu-name-main').first().fadeOut(200);
                        $('#wPaint').find('.wPaint-canvas').css({'cursor':'url(http://<?= IMAGES_URL ?>/picture/cursor-add-annotation.png),auto'});
                        break;
                    case 'magic':
                        $('#wPaint').find('.wPaint-canvas').css({'cursor':''});
                        $('#wPaint').find('.wColorPicker-theme-classic ').hide();
                        $('#wPaint').find('.wPaint-menu-name-main').first().fadeIn(200);
                        break;
                }
            });

            function nl2br (str, is_xhtml) {
                var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
                return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
            }

            ox = 0; oy = 0;

            $("#wPaint").click(function(e) {
                // stop $("#wPaint").click above
                e.stopPropagation();
                if($('#action').find('.active').attr('data-action') == 'comment' && ($(e.target).hasClass("wPaint-canvas"))) {
                        x = e.pageX;
                        y = e.pageY;
                        var offset = $(this).offset();
                        var top = y - offset.top ; var left = x - offset.left;
                        $('.changecmt').hide().appendTo('.divchangecmt');
                        $('.tick').css({'top' : top, 'left' : left, 'position' : 'absolute'}).show();
                        $('.comment').hide();
                    //Show div comment
                    id_submit = 0;
                    var x =''; var y ='';
                    x = e.pageX;
                    y = e.pageY;
                    var offset = $('.oneimg').find('.wPaint-canvas-bg').offset();
                    oy = y - offset.top ;  ox = x - offset.left;
                    $self = $(this);
                    $('.comment').find('.showcmt').html('');
                    $('.comment').css({'top' : oy, 'left' : ox + 25, 'position' : 'absolute'}).show('blind',300);
                    $('.comment').find('.cmt').focus().val('');
                }
            });

            //Show div comment when hover tickcmt
            $('#wPaint').on('mouseenter', '.tickcmt', function(){
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
                        $('.comment').css({'top':parseInt(y) , 'left':parseInt(x)+23,'position':'absolute' }).show();
                        $('.comment').find('.cmt').val('');
                    });
            });

            $('#wPaint').on('click', '.deletecmt', function() {
                $self1 = $(this);
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
                        $self1.parents('.divcmt').hide();
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

            $('#wPaint').on('click','.showdeletebtn',function() {
                $(this).hide();
                $(this).next().show();
            });

            $('#wPaint').on('click','.deletebtn',function() {
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

            $('#wPaint').on('click','.editcmt',function(){
                $self = $(this);
                $comment  = $self.parent('div').next();
                var value = $self.parent('div').next().find('span').html();
                var commentId = $self.parents('.divcmt').attr('data-id');
                $('.divcmt').find('.contentcmt').show();
                $self.parent('div').next().hide();
                var new_value = value.replace(/<br>/g,"");
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

            $('.oneimg').on('click', '.submit', function(){
                <?php if(!isset($session)): ?>
                $('.navbar-fixed-top').notify("You must login to comment",{position:'bottom right'});
                    return;
                <?php endif; ?>
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
                            a = new Date();
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
                            if(id_submit == 0){
                                $('.comment').hide();
                                button('<?= $user ?>', '<?= $newget ?>', '<?= $newget2 ?>');
                            }
                            //Notify by email when have guess comment

                                $.ajax({
                                    type :'post',
                                    url  :'/comment/notify',
                                    data :{
                                        send    : $user,
                                        receive : '<?= $user ?>',
                                        id_pro  : $id_pro,
                                        id_btn  : id_submit,
                                        url     : $(location).attr('href')
                                    }
                                }).done(function(response){
                                      console.log(response);
                                   });

                        });
                }
            });
            $('.changecmt').tooltip('show');

            <!-- wPaint -->

                $.extend($.fn.wPaint.defaults, {
                    mode:        'pencil',  // set mode
                    lineWidth:   '3',       // starting line width
                    fillStyle:   '#000000', // starting fill style
                    strokeStyle: '#000000'  // start stroke style
                });
                $.extend($.fn.wPaint.defaults, {
                    fontSize       : '12',    // current font size for text input
                    fontFamily     : 'Arial', // active font family for text input
                    fontBold       : false,   // text input bold enable/disable
                    fontItalic     : false,   // text input italic enable/disable
                    fontUnderline  : false    // text input italic enable/disable
                });

                $(window).load(function() {
                    $('#wPaint').css({
                        width :  $('#move').width(),
                        height: $('#move').height()
                    }).wPaint('resize');
                    $image = $('#move').attr('src');
                    $id = $('#move').attr('data-id');

                    $('#revision_image_wrapper').find('.revision_image').attr('src', $image).attr('data-id', $id);
                    var image = "<?= 'http://' . IMAGES_URL . '/picture/paint/wPaint-' ?>" + $id + '.png';
                    $('#revision_image_wrapper').append($("<img style='position: absolute' class='paintImage'/>").attr('src', image));
                    $('#revision_image_wrapper').find('img').last().error(function() {
                        $('#revision_image_wrapper').find('img').last().remove();
                    });
                    $('#move').hide();
                });

                function saveImg(image) {
                    <?php if( $session != $user): ?>
                        $('.navbar-fixed-top').notify("You don't have privilege to save image",{position:'bottom right'});
                        return;
                    <?php endif; ?>
                    $('.opacity').show().css({'cursor':'progress'});
                    $id =  $('#revision_image_wrapper').find('.revision_image').attr('data-id');
                    $.ajax({
                        type: 'POST',
                        url: '/upload.php',
                        data: {
                            image: image,
                            id   : $id
                        },success: function (resp) {
                            $('.opacity').hide();
                            $('#revision_image_wrapper').find('.paintImage').remove();
                            $.notify("Image saved successfully", "success");
                        },error: function (response){
                            $('.opacity').hide();
                            $.notify("Fail to save image", "error");
                        }
                    });
                }

                // init wPaint
                $('#wPaint').wPaint({
                    path: 'http://<?= ASSETS_URL ?>/asset/vendor/wPaint/',
                    menuOffsetLeft: 0,
                    menuOffsetTop: -50,
                    saveImg: saveImg
                });
                $('.wPaint-menu-icon-name-save').attr('title','Save Image .Note: if you save image, the work with pain before will be lost');
                $('.wPaint-menu-icon-name-loadBg').remove();
//                $('.wPaint-menu-icon-name-text ').remove();
                $('#wPaint').find('.wPaint-menu-alignment-horizontal').first().css({'width':'546','position':'relative'});

            $('.wPaint-menu-icon-name-rectangle').click(function(){
                $(this).find('.wPaint-menu-select-holder').toggle();
            });
            $('.wPaint-menu-icon-name-ellipse').click(function(){
                $(this).find('.wPaint-menu-select-holder').toggle();
            });

        });
    </script>
@endsection