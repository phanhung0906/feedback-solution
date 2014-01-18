<?php

class UserModel
{
    const ERROR_EXIST_USER = -1;
    const ERROR_NAME_USER = -2;
    const SUCCESS = -3;
    protected $passwordHash;

    public function __construct()
    {
        $this->passwordHash = new PasswordHash(8, false);
    }

    public function register($userName, $password, $email)
    {
        if ($userName == 'logout' || $userName == 'private' || $userName == 'public' || $userName == 'setting') {
            return UserModel::ERROR_EXIST_USER;
        }
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            $hashPassword =  $this->passwordHash->HashPassword($password);
            DB::insert('INSERT INTO user (user, passwd, email) values (?, ?, ?)', array($userName, $hashPassword, $email));
            return UserModel::SUCCESS;
        }
        return UserModel::ERROR_NAME_USER;
    }

    public function login($userName, $password)
    {
        $result = DB::table('user')->where('user', $userName)->first();
        return (($result != null) && $this->passwordHash->CheckPassword($password, $result->passwd));
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $result = DB::table('user')->where('user', $userName)->first();
        if ($this->passwordHash->CheckPassword($oldpass, $result->passwd)) {
            $hashPassword =  $this->passwordHash->HashPassword($newpass);
            DB::update('UPDATE user SET passwd = ? WHERE user = ?', array($hashPassword, $userName));
            return true;
        }
        return false;
    }

    public function forgotPassword($email)
    {
        $result = DB::table('user')->where('email',$email)->first();
        $token = uniqid();
        DB::table('password_remind')->where('email',$email)->delete();
        DB::table('password_remind')->insert(
            array('email' => $email, 'token' => $token)
        );
        if ($result != null) {
           return $list = array('email' => $email,'token' => $token);
        }
        return false;
    }

    public function checkToken($email,$token)
    {
        $result = DB::table('password_remind')->where('token',$token)->where('email',$email)->first();
        if ($result != null) {
            return true;
        }
        return false;
    }

    public function resetPassword($email,$token,$password)
    {
        $hashPassword =  $this->passwordHash->HashPassword($password);
        $result = DB::table('password_remind')->where('token',$token)->where('email',$email)->first();
        DB::table('password_remind')->where('email',$email)->delete();
        if ($result != null) {
            DB::table('user')->where('email', $result->email)->update(array('passwd' => $hashPassword));
            return true;
        }
        return false;
    }

    public function userEmail($user, $id_btn)
    {
        $result1 = DB::table('user')->where('user',$user)->first();
        $listEmail = array(
            'owner' => $result1->email,
            'guess' => array()
        );
        if ($id_btn == 0) {
           //Do nothing
        } else {
            $result2 = DB::table('user')
                ->join('comment', 'user.user', '=', 'comment.user')
                ->where('comment.id_btn',$id_btn)
                ->select('user.email')->get();
//            print_r($result2);die();
//            $result2 = DB::table('comment')->where('id_btn',$id_btn)->get();
//            foreach ($result2 as $result2) {
//                $result3 = DB::table('user')->where('user',$result2->user)->get();
//            }
//            foreach ($result3 as $result3) {
//                if ($result3->user == $user) continue;
//                $listEmail['guess'][] = $result3->email;
//            }
            $num = count($result2);
            for ($i = 0;$i < $num;$i++) {
                $compare = $result2[$i];
                for ($j = 1;$j < $num;$j++) {
                    if($result2[$j] == $compare) {
                        unset($result2[$j]);
                        $i++;
                    }
                }
            }
            foreach($result2 as $result2) {
               $listEmail['guess'][] = $result2->email;
            }
        }
        return $listEmail;
    }
}
