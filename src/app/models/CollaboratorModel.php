<?php
Class CollaboratorModel extends Eloquent{

    function addCollaborator($mission_name,$value){
        if($value == 'public' || $value == 'private'){
            DB::update('UPDATE mission SET collaborators= ? WHERE mission_name= ?',array($value,$mission_name));
            return "OK";
        }
        $alluser = array();
        $result = DB::select('SELECT * FROM user');
        foreach($result as $result){
            $alluser[] = $result->user;
        }
        $n = false ;
        $num_user = count($alluser);
        for($i=0;$i<$num_user;$i++){
            if($value == $alluser[$i]){
                $n = true;
                break;
            }
        }
        if($n == false){
            return "error1";
        }
        $result1 = DB::select('SELECT * FROM mission WHERE mission_name= ?',array($mission_name));
        $share_user = $result1[0]->collaborators;
        if($share_user == 'public' || $share_user == 'private'){
            DB::update('UPDATE mission SET collaborators= ? WHERE mission_name= ?',array($value,$mission_name));
            return "OK";
        }else{
            $share_user_array = explode(',',$share_user);
            $num = count($share_user_array);
            for($i=0;$i<$num;$i++){
                if($share_user_array[$i] == $value)
                    return 'error2';
            }
            $new_share_user = $share_user.','.$value;
            DB::update('UPDATE mission SET collaborators= ? WHERE mission_name= ?',array($new_share_user,$mission_name));
            return "OK";
        }
    }

    function deleteCollaborator($mission,$user){
        $result = DB::select('SELECT * FROM mission WHERE mission_name= ?',array($mission));
        $listUser = $result[0]->collaborators;
        $listUser_array = explode(',',$listUser);
        $num = count($listUser_array);
        for($i=0 ;$i<$num;$i++){
            if($listUser_array[$i] == $user){
                // delete element of Array
                unset($listUser_array[$i]);
            }
        }
        if(count($listUser_array) == 0){
            DB::update("UPDATE mission SET collaborators='private' WHERE mission_name= ?",array($mission));
            return "OK";
        }
        $new_share = implode(",", $listUser_array);
        DB::update("UPDATE mission SET collaborators= ? WHERE mission_name= ?",array($new_share,$mission));
        return "OK";
    }

    function listCollaborator($mission){
        $result = DB::select('SELECT * FROM mission WHERE mission_name= ?',array($mission));
        $listUser = $result[0]->collaborators;
        $listUser_array = explode(',',$listUser);
        if($listUser_array[0] == 'public'){
            return json_encode(array('public'));
        }
        if($listUser_array[0] == 'private'){
            return json_encode(array('private'));
        }
        return json_encode($listUser_array);
    }

    function userCollaborator($user,$mission){
        $list=array(
            'result' =>array(),
        );
        $alluser=array();
        $result = DB::select('SELECT * FROM user WHERE user != ?',array($user));
        foreach($result as $result){
            $alluser[]= $result->user;
        }
        $result1 = DB::select('SELECT * FROM mission WHERE mission_name = ?',array($mission));
        $share = $result1[0]->collaborators;
        $share_array = explode(',',$share);
        $num_alluser = count($alluser);
        $num_share_array = count($share_array);
        for($i=0;$i<$num_alluser;$i++){
            $count =0;
            for($j=0;$j<$num_share_array;$j++){
                if($alluser[$i] == $share_array[$j]){
                    break;
                }else{
                    $count++;
                    if($count == $num_share_array)
                        $list['result'][] = $alluser[$i];
                }
            }
        }
        return json_encode($list);
    }
}