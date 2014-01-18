<?php

class UserController extends Controller
{
    protected $userModel;
    protected $projectModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->projectModel = new ProjectModel();
    }

    public function filter()
    {
        if (!Session::has('user')) {
            $error = View::make('error.notlogin');
            return View::make('user.login')->with('error',$error);
        }
    }

    public function getLogin()
    {
        if (!Session::has('user')) {
            return View::make('user.login')->with('error', '');
        }
        return Redirect::to('/');
    }

    public function getRegister()
    {
        if (!Session::has('user')) {
            return View::make('user.register')->with('error', '');
        }
        return Redirect::to('/');
    }

    public function postRegister()
    {
        $error1   = View::make('error.register1');
        $error2   = View::make('error.register2');
        $userName = Input::get('user_name');
        $password = Input::get('password');
        $email    = Input::get('email');
        $response = $this->userModel->register($userName, $password, $email);
        switch($response){
            case (UserModel::ERROR_EXIST_USER):
                return View::make('user.register')->with('error', $error1);
                break;
            case (UserModel::ERROR_NAME_USER):
                return View::make('user.register')->with('error', $error2);
                break;
            case (UserModel::SUCCESS):
                Session::put('user', $userName);
                return Redirect::to('/');
                break;
            default:
                break;
        }
    }

    public function postLogin()
    {
        $error    = View::make('error.login');
        $userName = Input::get('user_name');
        $password = Input::get('password');
        $response = $this->userModel->login($userName, $password);
        if ($response == true) {
            Session::put('user', $userName);
            return Redirect::to('/');
        }
        return View::make('user.login')->with('error', $error);
    }

    public function changeAction()
    {
        $oldpass  = Input::get('oldpass');
        $newpass  = Input::get('newpass');
        $confirm  = Input::get('confirm');
        $user     = Session::get('user');
        $get      = '';
        $list     = $this->projectModel->find($user, $get);
        $error    = View::make('error.password');
        $response = $this->userModel->change($oldpass, $newpass, $confirm, $user);
        $data     = array(
                        'user'        => $user,
                        'session'     => $user,
                        'error'       => $error,
                        'project'     => $list['result'],
                    );
        if ($response == true) {
            return Redirect::to('/');
        }
        return View::make('user.password',$data);
    }

    public function logoutAction()
    {
//        Session::forget('user');
        $facebook = new Facebook(array(
            'appId'  => '635426773182038',
            'secret' => 'd839693141f1975ccbb9fdcb7eca3567',
        ));
        $uid = $facebook->getUser();
        if($uid != 0) {
            $logoutUrl = $facebook->getLogoutUrl(array(
                'next' => 'http://'.$_SERVER['SERVER_NAME']
            ));
            session_destroy();
            return Redirect::to($logoutUrl);
        }
        session_destroy();
        return Redirect::to('/');
    }

    public function forgotPassword()
    {
        $email  = Input::get('email');
        $list   = $this->userModel->forgotPassword($email);
        $link   = ROOT_URL.'/password/reset/'.$list['email'].'/'.$list['token'];
        $user   = array(
            'email' => $email,
            'name'  => 'pentool'
        );
        $data = array(
            'detail' => 'Your awesome detail here',
            'link'   => $link
        );
        Mail::send('emails.welcome', $data, function ($message) use ($user) {
            $message->from('htd530@gmail.com', 'pen.apl.vn');
            $message->to($user['email'], $user['name'])->subject('Resset your password');
        });
        return View::make('index')->with('error', View::make('error.forgotPassword'));
    }

    public function receiveMail($email,$token)
    {
        if ( $this->userModel->checkToken($email,$token)){
            return View::make('emails.auth.reset');
        }
        return Redirect::to('/');
    }

    public function sendMail($email,$token)
    {
        $credentials = array(
            'password'              => Input::get('newpass'),
            'password_confirmation' => Input::get('confirm')
        );
        if($this->userModel->resetPassword($email,$token,$credentials['password'])) {
            return Redirect::to('/login');
        }
        return Redirect::to('/');
    }

    public function notify()
    {
        $send    = $_POST['send'];
        $receive = $_POST['receive'];
        $id_pro  = $_POST['id_pro'];
        $id_btn  = $_POST['id_btn'];
        $email   = $this->userModel->userEmail($receive,$id_btn);
        $link    = $_POST['url'];
        $data    = array(
                        'send'   => $send,
                        'receive'=> $receive,
                        'link'   => $link,
                        'email' => $email['guess']
                    );
        $owner    = array(
                        'email' => $email['owner'],
                        'send'  => $send
                    );
        //php-resque
        $args = array('data' => $data,'owner' => $owner);
        Resque::setBackend('localhost:6379');
        Resque::enqueue('SendEmail', 'SendMail', $args);
        $token =   Resque::enqueue('SendEmail', 'SendMail', $args, true);
        echo $token.'<br>';
        $status = new Resque_Job_Status($token);
        echo $status->get();
        //Send mail to owner
//        if ($send != $receive) {
//            Mail::queueOn('hungArray','emails.notify', $data, function($message) use ($owner)
//            {
//                $message->from('htd530@gmail.com', 'pen.apl.vn');
//                $message->to($owner['email'], $owner['send'])->subject('Notify from pentool');
//            });
//        }
        //Send mail to guess
//        $num = count($email['guess']);
//        if ($num != 0) {
//            $guess = array(
//                'email' => $email['guess'],
//                'send'  => $send
//            );
//            for ($i = 0; $i < $num ; $i++) {
//                Mail::queueOn('hungArray','emails.guessMail', $data, function($message) use ($guess,$i)
//                {
//                    $message->from('htd530@gmail.com', 'pen.apl.vn');
//                    $message->to($guess['email'][$i], $guess['send'])->subject('Notify from pentool');
//                });
//            }
//        }
//        return  print_r($args);
    }
}
