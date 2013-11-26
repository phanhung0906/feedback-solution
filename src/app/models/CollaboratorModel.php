<?php

class CollaboratorModel
{
    const SUCCESS = 'OK';
    const ERROR_NO_USER = 'error1';
    const ERROR_SAME_USER = 'error2';
    protected $projectModel;

    public function add($mission, $value)
    {
        if ($value == 'public' || $value == 'private') {
            DB::update('UPDATE mission SET collaborators= ? WHERE mission_name= ?', array($value, $mission));
            return CollaboratorModel::SUCCESS;
        }
        $allUser = array();
        $result = DB::select('SELECT * FROM user');
        foreach($result as $result) {
            $allUser[] = $result->user;
        }
        $check = false ;
        $count = count($allUser);
        for ($i = 0; $i < $count; $i++) {
            if ($value == $allUser[$i]) {
                $check = true;
                break;
            }
        }
        if ($check == false) {
            return CollaboratorModel::ERROR_NO_USER;
        }
        $result1 = DB::table('mission')->where('mission_name', $mission)->first();
        $share = $result1->collaborators;
        if ($share == 'public' || $share == 'private') {
            DB::update('UPDATE mission SET collaborators= ? WHERE mission_name= ?', array($value, $mission));
            return CollaboratorModel::SUCCESS;
        } else {
            $shareArray = explode(',', $share);
            $num = count($shareArray);
            for ($i = 0; $i < $num; $i++) {
                if($shareArray[$i] == $value)
                    return CollaboratorModel::ERROR_SAME_USER;
            }
            $newShare = $share.','.$value;
            DB::update('UPDATE mission SET collaborators= ? WHERE mission_name= ?', array($newShare, $mission));
            return CollaboratorModel::SUCCESS;
        }
    }

    public function delete($mission, $user)
    {
        $result = DB::select('SELECT * FROM mission WHERE mission_name= ?', array($mission));
        $listUser = $result[0]->collaborators;
        $listUserArray = explode(',', $listUser);
        $num = count($listUserArray);
        for ($i = 0 ; $i < $num; $i++){
            if ($listUserArray[$i] == $user) {
                // delete element of Array
                unset($listUserArray[$i]);
            }
        }
        if (count($listUserArray) == 0) {
            DB::update("UPDATE mission SET collaborators='private' WHERE mission_name= ?", array($mission));
            return true;
        }
        $newShare = implode(",", $listUserArray);
        DB::update("UPDATE mission SET collaborators= ? WHERE mission_name= ?", array($newShare,$mission));
        return true;
    }

    public function find($mission)
    {
        $result = DB::table('mission')->where('mission_name', $mission)->first();
        $listUser = $result->collaborators;
        $listUserArray = explode(',', $listUser);
        if ($listUserArray[0] == 'public') {
            return json_encode(array('public'));
        }
        if($listUserArray[0] == 'private') {
            return json_encode(array('private'));
        }
        return json_encode($listUserArray);
    }

    public function findUser($user, $mission)
    {
        $list = array(
            'result' => array(),
        );
        $alluser = array();
        $result  = DB::select('SELECT * FROM user WHERE user != ?', array($user));
        foreach ($result as $result) {
            $alluser[] = $result->user;
        }
        $result1 = DB::table('mission')->where('mission_name', $mission)->first();
        $share = $result1->collaborators;
        $shareArray = explode(',', $share);
        $countUser = count($alluser);
        $countShareArray = count($shareArray);
        for ($i = 0; $i < $countUser; $i++) {
            $count =0;
            for ($j = 0; $j < $countShareArray; $j++) {
                if ($alluser[$i] == $shareArray[$j]) {
                    break;
                } else {
                    $count++;
                    if($count == $countShareArray)
                        $list['result'][] = $alluser[$i];
                }
            }
        }
        return json_encode($list);
    }
}
