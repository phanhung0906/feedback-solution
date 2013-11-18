<?php
Class UserController extends Controller {
    protected $userModel;

    function __construct(){
        $this->userModel = new UserModel();
    }

    public function getLogin(){
        if(!Session::has('user')){
        return View::make('user.login')->with('error','');
        } else return Redirect::to('/'.Session::get('user'));
    }

    public function getRegister(){
        if(!Session::has('user')){
        return View::make('user.register')->with('error','');
        }else return Redirect::to('/'.Session::get('user'));
    }

    function postRegister(){
        $userName = Input::get('user_name');
        $password = Input::get('password');
        return $this->userModel->register($userName,$password);
    }

    function postLogin(){
        $userName = Input::get('user_name');
        $password = Input::get('password');
        return $this->userModel->login($userName,$password);
    }


     function password(){
        $oldpass = Input::get('oldpass');
        $newpass = Input::get('newpass');
        $confirm = Input::get('confirm');
        $userName = Session::get('user');
        return $this->userModel->password($oldpass,$newpass,$confirm,$userName);
    }

    function logout(){
        Session::forget('user');
        return Redirect::to('/');
    }
}