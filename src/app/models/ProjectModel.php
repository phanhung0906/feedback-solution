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
                return "false";
            }
        }
        DB::insert('INSERT INTO mission (user,mission_name,collaborators) values (?, ?,?)', array($user,$mission_name, 'public'));
        return "OK";
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
        return "OK";
    }

    function editProject($id,$mission_name){
        $temp = explode('(',$mission_name);
        if(count($temp) > 1){
            return 'false';
            die();
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
            'numImg'     =>array()
        );
        $results = DB::select('SELECT * FROM mission WHERE user = ? ORDER BY id DESC', array($user));
        foreach ($results as $results)
        {
            $list['result'][] = $results;
        }
        $num = count($list['result']);
        for($i=0;$i<$num ;$i++){
            $result2 = DB::select('SELECT COUNT(id_pro) AS numImg FROM project WHERE mission_name = ?', array($list['result'][$i]->mission_name));
            $list['numImg'][] = $result2[0]->numImg;
        }
        return json_encode($list);
    }

    function ProjectImg($user){
        $list = array(
            'result'     => array(),
        );
        $missionArray = array();
        $no_img = "/picture/no_image.gif";
        $results = DB::select('SELECT * FROM mission where user = ?  ORDER BY id DESC', array($user));
        foreach ($results as $results)
        {
            $missionArray[] = $results;
        }

        $num = count($missionArray);
        for($i = 0 ;$i < $num; $i++){
            $projectArray = array();
            $results2 = DB::select('SELECT * FROM project where user = ?  AND BINARY mission_name= ?', array($user,$missionArray[$i]->mission_name));
            foreach ($results2 as $results2)
            {
                $projectArray[] = $results2->id_pro;
            }
            if($projectArray ==null ){
                $arrayTemp =array(
                    'mission' => $missionArray[$i]->mission_name,
                    'img'     =>  $no_img,
                    'id'      => $missionArray[$i]->id,
                    'collaborators' =>(string)$missionArray[$i]->collaborators,
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
            );
            $list['result'][] = $arrayTemp;
        }
            return json_encode($list);
    }
}