@extends('layout.template')
@section('content')
<div class="opacity" style="display:none">
    <div style="position: fixed;top:0;left:0;right:0;bottom:0;background: black;opacity: 0.4;width:100%;height:100%;z-index: 1"></div>
    <div style="z-index: 2;position: fixed;top:200px;width:100%">
        <div style="margin: 0 auto;text-align: center">
            <img src="http://<?= IMAGES_URL ?>/picture/ajax-loader.GIF" />
        </div>
    </div>
</div>

<div class="allpage">
    <div class="container">
               @include('layout.header')
        <section>
            @yield('page')
        </section>
    </div>

    <!-- div temp  -->
    <div class="divchangename hide">
        <input class="form-control changename" style="display: none;width:135px;display: inline-block" data-placement="bottom" title="hit Enter to change or Esc to exit" placeholder="Enter name..." >
    </div>

    <div class="removetemp hide">
        <a class="btn btn-default btn-block delete" data-id='#id#'>Remove</a>
    </div>

    <div class="missionPageTemp hide">
        <a data-page="#page#">#numpage#</a>
    </div>
    <div class="pictemp hide">
        <li class='pull-left'>
            <span class="picture">
                  <img src="http://<?= IMAGES_URL ?>#url#" data-id="#id#" class="img img-thumbnail" style="box-shadow: 3px 3px 30px;"/>
            </span>
        </li>
    </div>

    <!-- temp -->
</div>
<script type="text/javascript">
    document.onselectstart = new Function ("return false");
    $(document).ready(function(){
        $('section').css({'height':'100%'});
        function listMission(page,user){
            $PERPAGE = 10;
            $.ajax({
                type:'post',
                url:'http://<?= ROOT_URL.'/listproject' ?>',
                dataType:'json',
                data:{
                    user: user
                }
            }).done(function(response){
                    $('.menu').html('');
                    if(response.result==''){
                        $('.showmenu').find('.active').hide();
                    }else{
                        // Show menu and choose Project in upload
                        $('#target').show();
                        $('.chooseProject').show();
                        $('.projectmenu').show();
                    }
                    var num = response.result.length;
                    var num1 = num;
                    for(var i =0; i< num ; i++){
                        <?php if( Session::has('user') && isset($_GET['user'])): ?>
                            <?php if( Session::get('user') != $_GET['user']): ?>
                                if(response.result[i].collaborators != "public"){
                                    if( response.result[i].collaborators == "private"){
                                        num1--;
                                        continue;
                                    }
                                    var userArray = response.result[i].collaborators.split(',');
                                    var numarray = userArray.length;
                                    var temp = true;
                                    for(var q=0 ; q< numarray; q++){
                                        if( userArray[q] == "<?= Session::get('user') ?>"){
                                            temp = false;
                                        }
                                    }
                                    if(temp){
                                        num1--;
                                        continue;
                                    }
                                }
                            <?php endif; ?>
                        <?php endif; ?>
                        var template = $('.option').html().replace(/#mission#/g,response.result[i].mission_name);
                        $('.missionForm').find('.listMission').append(template);
                        var template2 = $('.missionMenu').html().replace(/#mission#/g,response.result[i].mission_name)
                            .replace(/#url#/g,'http://'+'<?= ROOT_URL ?>'+'/'+user+'/'+response.result[i].mission_name);
                        $('.menu').append(template2);
                    }
                    $($('.menu').html()).appendTo($('.showmenu').find('.project'));

                    <?php if(isset($_GET['project_name'])): ?>
                        <?php
                              $get = $_GET['project_name'];
                              $newget = str_replace("-"," ",$get);
                        ?>
                        var template = $('.missioncurrent').html().replace(/#mission#/g,'<?= $newget ?>');
                        $(template).appendTo($('.showmenu').find('.mission'));
                    <?php endif; ?>

                    $.ajax({
                        type:'post',
                        url:'http://<?= ROOT_URL.'/ProjectImg' ?>',
                        dataType:'json',
                        data:{
                            user: user
                        }
                    }).done(function(response2){
                            $('.missionPage').html('');
                            var current_page = parseInt(page);
                            if(num1%$PERPAGE == 0 ){
                                var num_link_page = parseInt(num1/$PERPAGE);
                            }else
                                var num_link_page = parseInt(num1/$PERPAGE) +1;
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
                                    $('.missionPage').html('');
                                    $('.missionImg').html('');
                                    $('.menu').html('');
                                    $page = $(this).find('a').attr('data-page');
                                    listMission($page,user);
                                }).appendTo('.missionPage');
                                if ( j != current_page ) {
                                    continue;
                                }
                                $('.missionImg').html('');
                                for (var k = start_page-1; k < end_page; k++) {
                                    <?php if( Session::has('user') && isset($_GET['user']) && (!isset($_GET['project_name']) && !isset($_GET['project']))): ?>
                                        <?php if( Session::get('user') != $_GET['user']): ?>
                                            if( response2.result[k].collaborators != "public" ){
                                                if( response2.result[k].collaborators == "private" ){
                                                    ++end_page;
                                                    continue;
                                                }
                                                var userArray = response2.result[k].collaborators.split(',');
                                                var numarray = userArray.length;
                                                var temp = true;
                                                for(var q=0 ; q< numarray; q++){
                                                    if( userArray[q] == "<?= Session::get('user') ?>"){
                                                        temp = false;
                                                    }
                                                }
                                                if(temp){
                                                    ++end_page;
                                                    continue;
                                                }
                                            }
                                        <?php endif; ?>

                                    var template2 = $('.missiontemp').html().replace(/#mission#/g,response2.result[k].mission)
                                        .replace(/#url#/g,response2.result[k].img)
                                        .replace(/#link#/g,'http://<?= ROOT_URL ?>/'+user+'/'+response2.result[k].mission)
                                        .replace(/#id#/g,response2.result[k].id)
                                        .replace(/#numImg#/g,response.numImg[k]);
                                    $('.missionImg').append(template2);
                                    <?php endif; ?>
                                }
                            }
                            if( j==2 ) $('.missionPage').html('');
                        });
                });
        }
        <?php if(isset($_GET['user'])): ?>
<!--              listMission(1,"--><?//= $_GET['user'] ?><!--");-->
        <?php elseif(Session::has('user')): ?>
<!--              listMission(1,"--><?//= Session::get('user') ?><!--");-->
        <?php endif; ?>
        $('.share').click(function(){
            $('.projectmenu').hide();
            $('.uploads').hide();
            $('.share').hide();
            $('.projectname').hide();
            $('.inputshare').show();
        });
        $('.back').click(function(){
            $('.projectmenu').show();
            $('.uploads').show();
            $('.share').show();
            $('.projectname').show();
            $('.inputshare').hide();
        });
        $('.divlink').find('ol').find('.active').mouseenter(function(){
            $(this).tooltip('show');
        });
    });
</script>
@endsection