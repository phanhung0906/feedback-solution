
<div class="navbar navbar-default navbar-fixed-top">
    <a class="navbar-brand" href="http://<?= ROOT_URL ?>">Feedback Solution</a>
    <div class="nav-collapse">

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
