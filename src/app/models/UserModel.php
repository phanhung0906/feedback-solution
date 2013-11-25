<?php
Class UserModel
{

    public function register($userName, $password)
    {
        if ($userName == null || $userName == 'private' || $userName == 'public' || $userName == ' ')
            return 'error1';
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            $temp = substr($password, strlen($password)-2, strlen($password));
            $newPassword = $password.$temp;
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, md5($newPassword)));
            return 'login';
        }else
            return 'error2';
    }

    public function login($userName, $password)
    {
        $temp = substr($password, strlen($password)-2, strlen($password));
        $newPassword = $password.$temp;
        $result = DB::select('SELECT * FROM user WHERE user = ? AND passwd = ?', array($userName, md5($newPassword)));
        if (count($result) != 0) {
            return true;
        } else
            return false;
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $temp = substr($oldpass, strlen($oldpass)-2, strlen($oldpass));
        $newPassword = $oldpass.$temp;
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if (md5($newPassword) == $result[0]->passwd) {
            if ($newpass == $confirm) {
                $temp = substr($newpass, strlen($newpass)-2, strlen($newpass));
                $newPasswordInsert = $newpass.$temp;
                DB::update('UPDATE user SET passwd = ? WHERE user = ?', array(md5($newPasswordInsert), $userName));
                return true;
            }
        } else
            return false;
    }
}
