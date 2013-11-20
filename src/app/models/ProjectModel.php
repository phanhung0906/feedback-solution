<?php

Class ProjectModel extends Eloquent{
    protected $table = 'mission';

    function addProject($user){
        $mission_name = $_POST['mission_name'];
        $array = array();
        $result = DB::select('SELECT * FROM mission WHERE user = ?',array($user));
        foreach($result as $result){
            $array[] = $result->mission_name;
        }
        for($i=0 ; $i < count($array); $i++ ){
            if($array[$i] == $mission_name){
                return false;
            }
        }
        DB::insert('INSERT INTO mission (user,mission_name,collaborators) values (?, ?,?)', array($user,$mission_name, 'public'));
        return true;
    }

    function deleteProject($id){
        $result = DB::select('SELECT * FROM mission WHERE id = ?',array($id));
        $mission_name = $result[0]->mission_name;
        DB::delete('DELETE FROM mission WHERE id= ?',array($id));
        $array= array(
            'id_pro'=>array()
        );
        $result2 = DB::select('SELECT * FROM project WHERE BINARY mission_name = ?',array($mission_name));
        foreach($result2 as $result2){
            $array['id_pro'][] = $result2->id_pro;
        }
        DB::delete('DELETE FROM project WHERE BINARY mission_name= ?',array($mission_name));
        $num = count($array['id_pro']);
        for($i=0;$i< $num; $i++){
            DB::delete('DELETE FROM comment WHERE id_pro= ?',array($array['id_pro'][$i]));
        }
        return true;
    }

    function editProject($id,$mission_name){
        $temp = explode('(',$mission_name);
        if(count($temp) > 1){
            return false;
        }
        $array = array();
        $result = DB::select('SELECT * FROM mission WHERE id != ?',array($id));
        foreach ($result as $result)
        {
            $array[] = $result->mission_name;
        }
        $result2 = DB::select('SELECT COUNT(id) AS num FROM mission WHERE id != ? AND (mission_name= ? OR mission_name LIKE ? )',array($id,$mission_name,$mission_name.'(%'));
        $n = $result2[0]->num;

        if($n > 0){
            $result3 = DB::select('SELECT * FROM mission WHERE id = ?',array($id));
            $name = $result3[0]->mission_name;
            $new_mission_name = $mission_name.'('.$n.')';
            for($i=0 ; $i< count($array);$i++){
                if($array[$i] == $new_mission_name){
                    --$n;
                    $new_mission_name = $mission_name.'('.$n.')';
                    $i =0;
                }
            }
            if($n == 0)  $new_mission_name = $mission_name;
            DB::update('UPDATE mission SET mission_name = ? WHERE id = ?', array($new_mission_name,$id));
            DB::update('UPDATE project SET mission_name = ? WHERE mission_name = ?', array($new_mission_name,$name));
            return $new_mission_name;
        }else{
            $result3 = DB::select('SELECT * FROM mission WHERE id = ?',array($id));
            $name = $result3[0]->mission_name;
            DB::update('UPDATE mission SET mission_name = ? WHERE id = ?', array($mission_name,$id));
            DB::update('UPDATE project SET mission_name = ? WHERE mission_name = ?', array($mission_name,$name));
            return $mission_name;
        }
    }

    function listProject($user){
        $list = array(
            'result'     => array(),
        );
        $result = array();
        $results = DB::select('SELECT * FROM mission WHERE user = ? ORDER BY id DESC', array($user));
        foreach ($results as $results)
        {
            $result[] = $results;
        }
        $num = count($result);
        for($i=0;$i<$num ;$i++){
            if( Session::has('user') && isset($_GET['user'])){
                if( Session::get('user') != $user ){
                    if( $result[$i]->collaborators != "public"){
                        if( $result[$i]->collaborators == "private"){
                            unset($result[$i]);
                            continue;
                        }
                        $userArray = explode(',',$result[$i]->collaborators);
                        $numarray = count($userArray);
                        $temp = true;
                        for($q=0 ; $q< $numarray; $q++){
                                if( $userArray[$q] == Session::get('user')){
                                    $temp = false;
                                }
                        }
                        if($temp){
                            unset($result[$i]);
                            continue;
                        }
                    }
                }
            }
        }
        foreach($result as $result){
            $list['result'][] = $result;
        }
        return $list;
    }

    function ProjectImg($user,$page){
        $PER_PAGE = 10;
        $list = array(
            'num_page' => 1,
            'result'     => array(),
            'numImg'     =>array()
        );
        $missionArray = array();
        $result = array();
        $no_img = "/picture/no_image.gif";
        $results = DB::select('SELECT * FROM mission where user = ?  ORDER BY id DESC', array($user));
        foreach ($results as $results)
        {
            $result[] = $results;
        }
        $num = count($result);
        for($i = 0 ;$i < $num; $i++){
            if( Session::has('user') && isset($_GET['user'])){
                if( Session::get('user') != $user ){
                    if( $result[$i]->collaborators != "public"){
                        if( $result[$i]->collaborators == "private"){
                            unset($result[$i]);
                            continue;
                        }
                        $userArray = explode(',',$result[$i]->collaborators);
                        $numarray = count($userArray);
                        $temp = true;
                        for($q=0 ; $q< $numarray; $q++){
                            if( $userArray[$q] == Session::get('user')){
                                $temp = false;
                            }
                        }
                        if($temp){
                            unset($result[$i]);
                            continue;
                        }
                    }
                }
            }
        }

        foreach($result as $result){
            $missionArray[] = $result;
        }
        $num = count($missionArray); /* num: number of project */
        $list['num_page'] = ceil($num / $PER_PAGE);
            $start_page = ($page-1)*$PER_PAGE+1;
            if ( $num > $page*$PER_PAGE){
                 $end_page = $page*$PER_PAGE;
            }else {
                $end_page = $num;
            }

        for($i = $start_page-1 ;$i < $end_page; $i++){
            $projectArray = array();
            $results2 = DB::select('SELECT * FROM project where user = ?  AND BINARY mission_name= ?', array($user,$missionArray[$i]->mission_name));
            $result2 = DB::select('SELECT COUNT(id_pro) AS numImg FROM project WHERE mission_name = ?', array($missionArray[$i]->mission_name));

            foreach ($results2 as $results2)
            {
                $projectArray[] = $results2->id_pro;
            }
            if($projectArray == null ){
                $arrayTemp = array(
                    'mission' => $missionArray[$i]->mission_name,
                    'img'     =>  $no_img,
                    'id'      => $missionArray[$i]->id,
                    'collaborators' =>(string)$missionArray[$i]->collaborators,
                    'num_img'   =>  $result2[0]->numImg
                );
                $list['result'][] = $arrayTemp;
                continue;
            }
            $num2 = count($projectArray);
            $projectId = 0;
            for($j = 0 ; $j < $num2 ; $j++){
                if( $projectId < $projectArray[$j] )
                    $projectId = $projectArray[$j];
            }
            $results3 = DB::select('SELECT * FROM project where user = ?  AND id_pro= ?', array($user,$projectId));
            $arrayTemp =array(
                'mission' => $missionArray[$i]->mission_name,
                'img'     =>  $results3[0]->url_square,
                'id'      => $missionArray[$i]->id,
                'collaborators' => (string)$missionArray[$i]->collaborators,
                'num_img'   =>  $result2[0]->numImg
            );
            $list['result'][] = $arrayTemp;
        }
            return $list;
    }
}