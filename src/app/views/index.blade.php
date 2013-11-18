@extends('layout.template')
@section('content')
    <h1 class="text-center" style="padding-top:12%;font-weight: bold;">Feedback Solution</h1>
    <h4 class="text-center" style="color: #848484;">The best way to feedback</h4>
    <div class="text-center" style="padding-top: 30px;">
        <a href="<?= "http://".ROOT_URL."/login" ?>" class=" btn btn-success btn-lg" style="margin-right: 10px;"> SIGNIN </a>
        <a href= "<?= "http://".ROOT_URL."/register" ?>" class="btn btn-primary btn-lg" > SIGNUP </a>
    </div>
@endsection
