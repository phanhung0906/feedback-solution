
<div class="menu hide">
</div>
<div class="navbar navbar-default navbar-fixed-top">
    <a class="navbar-brand" href="http://<?= ROOT_URL.'/'.Session::get('user') ?>">Feedback Solution</a>
    <div class="nav-collapse">

        <ul class="nav navbar-nav showmenu">
            <?php if(!isset($_GET['project'])): ?>
                <li data-id="Uploads" class="uploads"><a href="http://<?= ROOT_URL.'/setting/upload' ?>"><span class="fa fa-plus"></span> Upload</a></li>
            <?php endif; ?>
            <?php if(isset($_GET['project'])): ?>
                <?php $get = $_GET['project'];
                $newget = str_replace("-"," ",$get);
                ?>
                <li class="projectname"><a href="http://<?= ROOT_URL.'/'.Session::get('user').'/'.$_GET['project'] ?>"><span class="fa fa-arrow-left"></span> <?= $newget ?></a></li>
            <?php endif; ?>
            <li class="dropdown projectmenu" style="display:none">
                <a href="" class="dropdown-toggle" data-toggle="dropdown"> Project <b class="caret"></b></a>
                <ul class="dropdown-menu project">

                </ul>
            </li>
            <?php if((isset($_GET['project_name']) && !isset($_GET['action'])) || isset($_GET['project']) ): ?>

                <li class="share"><a style="cursor: pointer">Share</a></li>
                <div class="navbar-form navbar-left inputshare" style="display:none;margin-top: 18.5px;">
                    <span class="fa fa-arrow-left back" style="color:white;padding-right: 6px;padding-bottom:1px;border-right:1px solid;cursor: pointer"></span>
                    <span style="color:white;padding-left: 10px;"> Share </span>
                    <div class="form-group" style="padding-left: 5px;padding-right: 5px">
                        <input type="text" value="<?= 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; ?>" style="width:360px;">
                    </div>
                    <span style="color:white"> with colleagues to get feedback</span>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['project'])): ?>
                <div class="navbar-form navbar-left action">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default tool" data-action='comment'>
                            <input type="radio" name="options" id="option1"><span class="fa fa-comments-o"></span>
                        </label>
                        <label class="btn btn-default tool" data-action='move'>
                            <input type="radio" name="options" id="option2"><span class="fa fa-hand-o-up"></span>
                        </label>
                    </div>
                </div>
            <?php endif; ?>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="" class="dropdown-toggle glyphicon glyphicon-cog" data-toggle="dropdown"><b class="caret"></b></a>
                <ul class="dropdown-menu showmenu" style="width:200px">
                    <li>
                        <a href="http://<?= ROOT_URL.'/'.Session::get('user') ?>"><span class="fa fa-home"></span> Home</a>
                    </li>


                    <?php if(isset($_GET['project_name']) && Session::has('user') && isset($_GET['user'])): ?>
                        <?php if($_GET['user'] == Session::get('user')): ?>
                            <?php $link = $_SERVER["SERVER_NAME"]."/".$_GET['user']."/".$_GET['project_name']; ?>
                            <!-- Collaborators -->
                            <li role="presentation" class="divider"></li>
                            <li class="dropdown-header mission"></li>
                            <li><a href="http://<?= $link ?>/collaboration" data-id="Collaborators">Collaborators</a></li>
                            <!-- /Collaborators -->
                        <?php endif; ?>
                    <?php endif; ?>
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
    <div class="nav-collapse collapse">
        <ul class="nav pull-right">

        </ul>
    </div>

</div>
