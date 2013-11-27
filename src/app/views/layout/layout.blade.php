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

    <div class="missionMenu hide">
        <li><a href="#url#" data-id="#mission#">#mission#</a></li>
    </div>
    <div class="missioncurrent hide">
        <span>Project / #mission#</span>
    </div>
    <!-- temp -->
</div>
<script type="text/javascript">
    document.onselectstart = new Function ("return false");
    $(document).ready(function(){
        $('section').css({'height':'100%'});
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