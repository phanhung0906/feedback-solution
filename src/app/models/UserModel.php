<?php
Class UserModel
{
    const cryptString = '$2a$07$usesomesillystringforsalt$';
    public function register($userName, $password)
    {
        if ($userName == null || $userName == 'private' || $userName == 'public' || $userName == ' ')
            return 'error1';
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            $newPassword = crypt($password, UserModel::cryptString);
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, md5($newPassword)));
            return 'login';
        }else
            return 'error2';
    }

    public function login($userName, $password)
    {
        $cryptPassword = crypt($password, UserModel::cryptString);
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if (count($result) != 0) {
            if (md5($cryptPassword) == $result[0]->passwd)
                return true;
        } else
            return false;
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $newPassword = crypt($oldpass, UserModel::cryptString);
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if (md5($newPassword) == $result[0]->passwd) {
            if ($newpass == $confirm) {
                $newPasswordInsert = crypt($newpass, UserModel::cryptString);
                DB::update('UPDATE user SET passwd = ? WHERE user = ?', array(md5($newPasswordInsert), $userName));
                return true;
            }
        } else
            return false;
    }
}
