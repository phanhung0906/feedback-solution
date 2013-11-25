<?php
Class UserModel
{

    static function better_crypt($input, $rounds = 7)
    {
        $salt = "";
        $salt_chars = array_merge(range('A','Z'), range('a','z'), range(0,9));
        for($i=0; $i < 22; $i++) {
            $salt .= $salt_chars[array_rand($salt_chars)];
        }
        return crypt($input, sprintf('$2a$%02d$', $rounds) . $salt);
    }

    public function register($userName, $password)
    {
        if ($userName == null || $userName == 'private' || $userName == 'public' || $userName == ' ')
            return 'error1';
        $results = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if ($results == null) {
            $newPassword = UserModel::better_crypt($password);
            DB::insert('INSERT INTO user (user, passwd) values (?, ?)', array($userName, $newPassword));
            return 'login';
        }else
            return 'error2';
    }

    public function login($userName, $password)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if (count($result) != 0) {
            if(crypt($password, $result[0]->passwd) == $result[0]->passwd) {
                return true;
            }
        } else
            return false;
    }

    public function change($oldpass, $newpass, $confirm, $userName)
    {
        $result = DB::select('SELECT * FROM user WHERE user = ?', array($userName));
        if(crypt($oldpass, $result[0]->passwd) == $result[0]->passwd) {
            if ($newpass == $confirm) {
                $newPasswordInsert = UserModel::better_crypt($newpass);
                DB::update('UPDATE user SET passwd = ? WHERE user = ?', array($newPasswordInsert, $userName));
                return true;
            }
        } else
            return false;
    }
}
