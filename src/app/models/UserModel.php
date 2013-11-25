<?php

class UserModel
{
    const ERROR_EXIST_USER = -1;
    const ERROR_NAME_USER = -2;
    const SUCCESS = -3;
    protected $passwordHash;

    public function __construct(){
        $this->passwordHash = new PasswordHash(8, false);
    }

    public function register($userName, $password)
    {
        if ($userName == null || $userName == 'private' || $userName == 'public' || $userName == ' ') {
            return UserModel::ERROR_EXIST_USER;
        }
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            $hashPassword =  $this->passwordHash->HashPassword($password);
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, $hashPassword));
            return UserModel::SUCCESS;
        }
        return UserModel::ERROR_NAME_USER;
    }

    public function login($userName, $password)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        return (count($result) != 0 && $this->passwordHash->CheckPassword($password, $result[0]->passwd));
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($this->passwordHash->CheckPassword($oldpass, $result[0]->passwd)) {
            $hashPassword =  $this->passwordHash->HashPassword($newpass);
            DB::update('UPDATE user SET passwd = ? WHERE user = ?', array($hashPassword, $userName));
            return true;
        } else
            return false;
    }
}
