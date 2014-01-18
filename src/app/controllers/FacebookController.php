<?php
class FacebookController extends Controller
{
    protected $userModel;
    protected $facebookModel;
    protected $projectModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->facebookModel = new FacebookModel();
        $this->projectModel = new ProjectModel();
    }

    public function login()
    {
        $facebook = new Facebook(array(
            'appId'  => '635426773182038',
            'secret' => 'd839693141f1975ccbb9fdcb7eca3567',
        ));
        $params = array(
            'redirect_uri' => url('/login-facebook/callback'),
            'scope' => 'email',
        );
        return Redirect::to($facebook->getLoginUrl($params));
    }

    public function callback()
    {
        $code = Input::get('code');
        if (strlen($code) == 0)
            return Redirect::to('/')->with('error', 'There was an error communicating with Facebook');
        $facebook = new Facebook(array(
            'appId'  => '635426773182038',
            'secret' => 'd839693141f1975ccbb9fdcb7eca3567',
        ));
        $uid = $facebook->getUser();

        //Error get data user on facebook
        if ($uid == 0)
            return Redirect::to('/')->with('error', 'There was an error');

        //Login with facebook or register with facebook account
        $me = $facebook->api('/me');
        $userName = $me['username'];
        $email    = $me['email'];
        $password = $me['id'];
        $response = $this->userModel->login($userName, $password);
        if ($response == true) {
            Session::put('user', $userName);
            return Redirect::to('/');
        } else {
            $error1   = View::make('error.register1');
            $error2   = View::make('error.register2');
            $response = $this->userModel->register($userName, $password, $email);
            switch($response){
                case (UserModel::ERROR_EXIST_USER):
                    return View::make('index')->with('error', $error1);
                    break;
                case (UserModel::ERROR_NAME_USER):
                    return View::make('index')->with('error', $error2);
                    break;
                case (UserModel::SUCCESS):
                    Session::put('user', $userName);
                    return Redirect::to('/');
                    break;
                default:
                    break;
            }
        }
    }
}