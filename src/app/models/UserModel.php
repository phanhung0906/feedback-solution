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
}
