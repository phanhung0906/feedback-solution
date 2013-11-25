<?php
Class UserModel
{
    protected $passwordHash;

    public function __construct(){
        $this->passwordHash = new PasswordHash(8, FALSE);
    }

    public function register($userName, $password)
    {
        if ($userName == null || $userName == 'private' || $userName == 'public' || $userName == ' ')
            return 'error1';
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            $hashPassword =  $this->passwordHash->HashPassword($password);
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, $hashPassword));
            return 'login';
        }else
            return 'error2';
    }

    public function login($userName, $password)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if (count($result) != 0) {
            $check =  $this->passwordHash->CheckPassword($password, $result[0]->passwd);
            if ($check)
                return true;
        } else
            return false;
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        $check =  $this->passwordHash->CheckPassword($oldpass, $result[0]->passwd);
        if ($check){
            if ($newpass == $confirm) {
                $hashPassword =  $this->passwordHash->HashPassword($newpass);
                DB::update('UPDATE user SET passwd = ? WHERE user = ?', array($hashPassword, $userName));
                return true;
            }
        } else
            return false;
    }
}
