<?php
Class UserController extends Controller {
    protected $userModel;

    function __construct(){
        $this->userModel = new UserModel();
    }

    public function getLogin(){
        if(!Session::has('user')){
        return View::make('user.login')->with('error','');
        } else return Redirect::to('/');
    }

    public function getRegister(){
        if(!Session::has('user')){
        return View::make('user.register')->with('error','');
        }else return Redirect::to('/');
    }

    function postRegister(){
        $error1 = View::make('error.register1');
        $error2 = View::make('error.register2');
        $userName = Input::get('user_name');
        $password = Input::get('password');
        $response = $this->userModel->register($userName,$password);
        switch($response){
            case 'error1':
                return View::make('user.register')->with('error',$error1);
                break;
            case 'error2':
                return View::make('user.register')->with('error',$error2);
                break;
            case 'login':
                return Redirect::to('/');
                break;
            default:
                break;
        }
    }

    function postLogin(){
        $error = View::make('error.login');
        $userName = Input::get('user_name');
        $password = Input::get('password');
        $response = $this->userModel->login($userName,$password);
        if($response == true){
            return Redirect::to('/');
        }else return View::make('user.login')->with('error',$error);
    }


     function password(){
        $oldpass = Input::get('oldpass');
        $newpass = Input::get('newpass');
        $confirm = Input::get('confirm');
        $userName = Session::get('user');
        $user = Session::get('user');
        $data = $this->projectModel->listProject($user);
        $error = View::make('error.password');
        $response = $this->userModel->password($oldpass,$newpass,$confirm,$userName);
        if($response == true){
            return Redirect::to('/'.$userName);
        }else return View::make('user.password')->with('error',$error)->with('project',$data['result']);
    }

    function logout(){
        Session::forget('user');
        return Redirect::to('/');
    }
}