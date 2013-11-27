@extends('layout.layout')
@include('user.header')
@section('page')
<ol class="breadcrumb">
    <li><a href="http://<?= ROOT_URL ?>">Dasbboard</a></li>
    <li><a href="http://<?= ROOT_URL . '/' . $user . '/' . $get . '/page/1' ?>">{{$newget}}</a></li>
    <li class="active"><b>Collaborators</b></li>
</ol>
<div class="page">

    <div class="maintain">
        <div style="padding-left: 5%">
            <div class='page-header'>
                <h3>Collaboration</h3>
            </div>
            <div  style="margin-bottom: 10px;">
                {{$error}}
                <div class="alert alert-danger alertAddError1" style="display: none;">
                    There is user to be the same !
                </div>
                <div class="alert alert-danger alertAddError2" style="display: none;">
                    User not alive !
                </div>
            </div>

            <div class="formradio" style="width:50%">
                <div class="radio">
                    <label>
                        <input type="radio" name="optionsRadios" class="options" value="public" >
                        <span class="fa fa-globe"></span>
                        Public
                    </label>
                </div>
                <div class="radio">
                    <label>
                        <input type="radio" name="optionsRadios" class="options" value="private">
                        <span class="fa fa-lock"></span>
                        Private
                    </label>
                </div>
                <ul class="listUser" style="padding-left: 0;"></ul>
                <div class="formAddUser" style="margin-top: 10px;background:#f5f5f5;border:1px solid #ddd;padding:4px;">
                    <input class="form-control inputShareUser pull-left" placeholder="Enter user here..." style="width:85%;margin-right: 10px">
                    <button class="btn btn-default addUser" style="display: inline-block">Add</button>
                    <div class="user hide"><li class="alert username" style="margin:5px 5px 5px 0;padding: 10px;list-style: none;background: #999999 ;border:1px solid #ccc"><b class="user_share">#user#</b><span class="text-danger pull-right deleteUser" style="cursor: pointer;">(remove)</span></li></div>
                </div>
            </div>
        </div>
    </div>
</div>
        <script type="text/javascript">
            $(document).ready(function(){
                function listUser(mission){
                    $.ajax({
                        type:'post',
                        dataType:'json',
                        url:'http://<?= ROOT_URL ?>/userCollaborator',
                        data:{
                            user:"<?= $session ?>",
                            mission : mission
                        }
                    }).done(function(response){
                            var availableTags = new Array();
                            $num = response.result.length;
                            for(var i =0; i < $num; i++){
                                availableTags[i] =  response.result[i];
                            }
                            $( ".inputShareUser" ).autocomplete({
                                source: availableTags
                            });
                        });
                }

                listUser('<?= $newget ?>');

                function listCollaborators(mission){
                    $.ajax({
                        type:'post',
                        dataType:'json',
                        url:'http://<?= ROOT_URL ?>/listCollaborator',
                        data:{
                            mission : mission
                        }
                    }).done(function(response){
                            if(response[0] != 'public' && response[0] != 'private' ){
                                var num = response.length;
                                for(i=0;i<num;i++){
                                    var template = $('.user').html().replace(/#user#/,response[i]);
                                    $(template).appendTo('.listUser');
                                }
                                $('.formradio').find('.radio').find($('input[value=private]')).prop('checked', true);
                            }
                            if(response[0] == 'public' ){
                                $('.formradio').find('.radio').find($('input[value=public]')).prop('checked', true);
                            }
                            if(response[0] == 'private' ){
                                $('.formradio').find('.radio').find($('input[value=private]')).prop('checked', true);
                            }
                        });
                }
                listCollaborators('<?= $newget ?>');

                $('.listUser').on('click','.deleteUser',function(){
                    $('.opacity').show();
                    $self = $(this);
                    var user = $self.prev('.user_share').html();
                    $.ajax({
                        type:'post',
                        url:'http://<?= ROOT_URL ?>/deleteCollaborator',
                        data:{
                            mission :'<?= $newget ?>',
                            user : user
                        }
                    }).done(function(response){
                            $('.opacity').hide();
                            if(response){
                                $.notify("Delete success", "success");
                                $self.parent('li').remove();
                            }else
                                $.notify("Delete false", "error");
                        });
                });

                $('.addUser').click(function(){
                    $('.opacity').show();
                    $self = $(this);
                    var value = $self.prev('.inputShareUser').val();
                    $.ajax({
                        type:'post',
                        url:'http://<?= ROOT_URL ?>/addCollaborator',
                        data:{
                            mission_name :'<?= $newget ?>',
                            value : value
                        }
                    }).done(function(response){
                            $('.opacity').hide();
                            if(response == "OK"){
                                $.notify("Add success", "success");
                                $self.parents('.formradio').find('.radio').find($('input[value=private]')).prop('checked', true);
                                var template = $('.user').html().replace(/#user#/,value);
                                $(template).appendTo('.listUser');
                            }
                            if(response == "error2"){console.log('error1');
                                $('.alertAddError1').show().delay(2000).fadeOut();
                            }
                            if(response == "error1"){
                                $('.alertAddError2').show().delay(2000).fadeOut();
                            }
                        });
                });

                $('.radio').click(function(){
                    $self = $(this);
                    var value = $self.find('.options').val();
                    if(value == 'public'){
                        $('.formradio').find('.listUser').html('');
                        $('.formradio').find('.inputShareUser').val('');
                        $('.opacity').show();
                        $.ajax({
                            type:'post',
                            url:'http://<?= ROOT_URL ?>/addCollaborator',
                            data:{
                                mission_name :'<?= $newget ?>',
                                value : value
                            }
                        }).done(function(response){
                                $('.opacity').hide();
                                if(response == "OK"){
                                   //Do anything
                                }
                            });
                    }
                    if(value == 'private'){
                        var template =$('.formradio').find('.listUser').html();
                        if(template == ''){
                            $('.opacity').show();
                            $.ajax({
                                type:'post',
                                url:'http://<?= ROOT_URL ?>/addCollaborator',
                                data:{
                                    mission_name :'<?= $newget ?>',
                                    value : value
                                }
                            }).done(function(response){
                                    $('.opacity').hide();
                                    if(response == "OK"){
                                        //Do anything
                                    }
                                });
                        }
                    }
                });
            })
        </script>
@endsection