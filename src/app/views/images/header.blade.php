
<div class="menu hide">
</div>
<div class="navbar navbar-default navbar-fixed-top">
    <a class="navbar-brand" href="http://<?= ROOT_URL ?>">Feedback Solution</a>
    <div class="nav-collapse">

        <ul class="nav navbar-nav showmenu">
            @if($user == $session)
                <li class="dropdown projectmenu" <?php if(count($project) == 0)  echo "style='display: none;'" ?>>
                    <a href="" class="dropdown-toggle" data-toggle="dropdown"> Project <b class="caret"></b></a>
                    <ul class="dropdown-menu project" style="overflow: auto;max-height: 400px;">
                            @foreach($project as $project)
                                      <li><a href="http://<?= ROOT_URL . '/' . $user . '/' . $project->mission_name . '/page/1' ?>" data-id="<?= $project->mission_name ?>">{{$project->mission_name}}</a></li>
                            @endforeach
                    </ul>
                </li>
            <!-- Collaborators -->
                <?php $link = $_SERVER["SERVER_NAME"] . "/" . $user . "/" . $get; ?>
                <li class='collaborations'><a href="http://<?= $link ?>/collaboration" data-id="Collaborators">Collaborators</a></li>
            <!-- /Collaborators -->
            @endif
            <li class="share"><a style="cursor: pointer">Share</a></li>
            <div class="navbar-form navbar-left inputshare" style="display:none;margin-top: 18.5px;">
                <span class="fa fa-arrow-left back" style="color:white;padding-right: 6px;padding-bottom:1px;border-right:1px solid;cursor: pointer"></span>
                <span style="color:white;padding-left: 10px;"> Share </span>
                <div class="form-group" style="padding-left: 5px;padding-right: 5px">
                    <input type="text" value="<?= 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" style="width:360px;">
                </div>
                <span style="color:white"> with colleagues to get feedback</span>
            </div>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="" class="dropdown-toggle glyphicon glyphicon-cog" data-toggle="dropdown"><b class="caret"></b></a>
                @if($session != '')
                    <ul class="dropdown-menu showmenu" style="width:200px">
                        <li>
                            <a href="http://<?= ROOT_URL ?>"><span class="fa fa-home"></span> Home</a>
                        </li>

                        <!-- Change password -->
                            <li role="presentation" class="divider"></li>
                            <li class="dropdown-header"> Setting</li>
                            <li><a href="<?= "http://".ROOT_URL."/setting/password" ?>" data-id="Password"><span class=""></span> Password</a></li>
                        <!-- /Change password -->
                        <li role="presentation" class="divider"></li>
                        <li><a href="<?= "http://".ROOT_URL."/logout" ?>"><span class="fa fa-power-off"></span> Logout</a></li>
                    </ul>
                @endif
            </li>
        </ul>
    </div>
</div>

