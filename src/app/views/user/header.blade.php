<div class="menu hide">
</div>
<div class="navbar navbar-default navbar-fixed-top">
    <a class="navbar-brand" href="http://<?= ROOT_URL ?>">Feedback Solution</a>
    <div class="nav-collapse">

        <ul class="nav navbar-nav showmenu">
            <li data-id="Uploads" class="uploads"><a href="http://<?= ROOT_URL.'/setting/upload' ?>"><span class="fa fa-plus"></span> Upload</a></li>

            <li class="dropdown projectmenu" <?php if(count($project) == 0)  echo "style='display: none;'" ?>>
                <a href="" class="dropdown-toggle" data-toggle="dropdown"> Project <b class="caret"></b></a>
                <ul class="dropdown-menu project" style="overflow: auto;max-height: 400px;">
                        @foreach($project as $project)
                             <li><a href="http://<?= ROOT_URL . '/' . $session . '/' . $project->mission_name . '/page/1' ?>" data-id="<?= $project->mission_name ?>">{{$project->mission_name}}</a></li>
                        @endforeach
                </ul>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="" class="dropdown-toggle glyphicon glyphicon-cog" data-toggle="dropdown"><b class="caret"></b></a>
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
            </li>
        </ul>
    </div>
</div>
