<?php
Class UserModel extends Eloquent{
    protected $projectModel;

    public function __construct(){
        $this->projectModel = new ProjectModel;
    }

    function register($userName,$password){
        $error1 = View::make('error.register1');
        $error2 = View::make('error.register2');
        if($userName==null || $userName=='private' || $userName=='public' || $password==null || $userName==' ' || $password==' ')
            return View::make('user.register')->with('error',$error1);
        $array1 = explode(' ',$userName);
        if(count($array1) >1)
            return 'have space';
        if( strlen($password) < 3 ) return 5;
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if($results == null){
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, $password));
            Session::put('user',$userName);
            return Redirect::to('/');
        }else return View::make('user.register')->with('error',$error2);
    }

    function login($userName,$password){
        $error = View::make('error.login');
        $result = DB::select('SELECT * FROM user WHERE user = ? AND passwd = ?', array($userName,md5($password)));
        if(count($result) != 0){
            Session::put('user',$userName);
//            if(isset($_POST['rememberme'])=='on' ){
//                setcookie('user', $_POST['user_name'], time() + 24*60*60*7);
//            }else{
//                setcookie('user', $_POST['user_name'], time() + 3600);
//            }
            return Redirect::to('/');
        }else return View::make('user.login')->with('error',$error);
    }

    function password($oldpass,$newpass,$confirm,$userName){
        $user = Session::get('user');
        $data = $this->projectModel->listProject($user);
        $error = View::make('error.password');
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if( md5($oldpass) == $result[0]->passwd){
            if($newpass == $confirm){
                DB::update('UPDATE user SET passwd = ? WHERE user = ?', array(md5($newpass),$userName));
                return  Redirect::to('/'.$userName);
            }
        }else return View::make('user.password')->with('error',$error)->with('project',$data['result']);
    }
}