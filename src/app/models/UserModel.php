<?php
Class UserModel
{

    public function register($userName, $password)
    {
        if ($userName == null || $userName == 'private' || $userName == 'public' || $password == null || $userName == ' ' || $password == ' ')
            return 'error1';
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, md5($password)));
            Session::put('user', $userName);
            return 'login';
        }else
            return 'error2';
    }

    public function login($userName, $password)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ? AND passwd = ?', array($userName, md5($password)));
        if (count($result) != 0) {
            Session::put('user', $userName);
            return true;
        } else
            return false;
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if (md5($oldpass) == $result[0]->passwd) {
            if ($newpass == $confirm) {
                DB::update('UPDATE user SET passwd = ? WHERE user = ?', array(md5($newpass), $userName));
                return true;
            }
        } else
            return false;
    }
}