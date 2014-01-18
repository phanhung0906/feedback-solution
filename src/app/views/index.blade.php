@extends('layout.template')
@section('content')
{{$error}}

<div class="navbar navbar-default">
    <div class='container' style='max-width: 70%'>
        <a class="navbar-brand" href="http://<?= ROOT_URL ?>">Feedback Solution</a>
        <div class="nav-collapse">
            <ul class="nav navbar-nav">
                <li><a href="#plan">About us</a></li>
            </ul>
            <div class="pull-right">
                <ul class="nav navbar-nav">
                    <li><a href='<?= "http://".ROOT_URL."/login" ?>'>Login</a></li>
                </ul>
                <a href= "<?= "http://".ROOT_URL."/register" ?>" class="btn btn-info navbar-btn"><span class="fa fa-star"></span> Sign up</a>
            </div>
        </div>
    </div>
</div>

<div class="hero-unit" style="background: transparent url(http://cleancanvas.herokuapp.com/img/backgrounds/landscape.png) no-repeat 50% top;
min-height: 560px;
border-radius: 0;
margin-bottom: 0;
color: #fff;
padding: 0px;
-webkit-background-size: cover;">

    <div class="container" style='max-width: 70%'>
        <div class="hero-unit-inner text-center" style='padding-top: 50px'>
            <div class="text-in-hero" style="background: rgba(56,56,58,0.2);padding: 10px;margin-bottom: 20px;">
                <h2>Feedback Solution</h2>
                <h4>The best way to feedback</h4>
            </div>
            <div class="text-center" style='padding-top: 50px'>
                <h4>Click the button below to login with your Facebook account</h4>
                <a id="fb-auth" href='/login-facebook'>
                    <img src='https://ilovecuteshoes.com/skin/frontend/gravdept/acumen/img/facebook-login-button.png' style='margin:20px;'/>
                </a>

                <h4 style='margin-bottom: 30px'>Sign up and  design with teams in mind to make review, sharing and concepting easier than ever.</h4>
                <a href= "<?= "http://".ROOT_URL."/register" ?>" class="btn btn-info btn-lg" > Get Start for FREE</a>
            </div>
        </div>
    </div>
</div><!--End hero-unit-->

<div id="plan" style='padding: 100px 0px;'>
    <div class="container" style='max-width: 70%'>
        <div class="row">
            <div class="col-md-4 text-center">
                <span class="fa fa-flag" style='font-size: 3em'></span>
                <h4>WHAT IS NOTEPEN?</h4>
                <p>NotePen is a web platform to help designers and organizations manage design feedback & revisions with simple visual collaboration tools.</p>
            </div>
            <div class="col-md-4 text-center">
                <span class="fa fa-star" style='font-size: 3em'></span>
                <h4>WHY USE NOTEPEN?</h4>
                <p>Designers are visually oriented people and prefer feedback visually. NotePen gives its users a set of visual tools through which they can collaborate and work with each other on designs, manage multiple revisions and track comments all from one simple interface.</p>
            </div>
            <div class="col-md-4 text-center">
                <span class="fa fa-user" style='font-size: 3em'></span>
                <h4>WHO'S USING NOTEPEN?</h4>
                <p>We are proud to have some of of the worlds best known brands using NotePen. Please check our testimonials page to see why our clients love using NotePen. </p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4 text-center">
                <span class="fa fa-briefcase" style='font-size: 3em'></span>
                <h4> Manage your projects</h4>
                <p>Manage clients, projects, designs, revisions all from one neat interface</p>
            </div>
            <div class="col-md-4 text-center">
                <span class="fa fa-bullhorn" style='font-size: 3em'></span>
                <h4>Collaborate and Discuss</h4>
                <p>Use our awesome annotation tools to mark up changes, use comments to leave comments and reply to comments</p>
            </div>
            <div class="col-md-4 text-center">
                <span class="fa fa-download" style='font-size: 3em'></span>
                <h4>Drag and Drop your files</h4>
                <p>Simply Drag and drop directly into your browser to upload files into our cloud. It couldnt get any simpler to start collaborating </p>
            </div>
        </div>
    </div>
</div>

<footer style='background-color: #2C3E50;padding:20px;color: #ffffff'>
    <div class='container' style='max-width: 70%'>
        &copy 2014. All Rights Reserved
    </div>
</footer>
<script>
    $(document).ready(function(){
        $('body').css({'paddingTop':0});
        $('.navbar-default').css({'marginBottom':0,'borderRadius':0});
    })
</script>
@endsection
