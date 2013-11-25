<?php
Class ProjectModel
{

    public function add($user, $missionName)
    {
        $array  = array();
        $result = DB::select('SELECT * FROM mission WHERE user = ?', array($user));
        foreach ($result as $result) {
            $array[] = $result->mission_name;
        }
        for ($i=0 ; $i < count($array); $i++ ) {
            if ($array[$i] == $missionName) {
                return false;
            }
        }
        DB::insert('INSERT INTO mission (user,mission_name,collaborators) values (?, ?,?)', array($user, $missionName, 'public'));
        return true;
    }

    public function delete($id)
    {
        $result      = DB::select('SELECT * FROM mission WHERE id = ?', array($id));
        $missionName = $result[0]->mission_name;
        DB::delete('DELETE FROM mission WHERE id= ?', array($id));
        $arrayImage = array(
            'id_pro' => array()
        );
        $result2 = DB::select('SELECT * FROM project WHERE BINARY mission_name = ?', array($missionName));
        foreach ($result2 as $result2) {
            $arrayImage['id_pro'][] = $result2->id_pro;
        }
        DB::delete('DELETE FROM project WHERE BINARY mission_name= ?', array($missionName));
        $num = count($arrayImage['id_pro']);
        for ($i = 0; $i < $num; $i++) {
            DB::delete('DELETE FROM comment WHERE id_pro= ?', array($arrayImage['id_pro'][$i]));
            DB::delete('DELETE FROM button WHERE id_pro= ?', array($arrayImage['id_pro'][$i]));
        }
        return true;
    }

    public function edit($id, $missionName, $user)
    {
        $temp = explode('(', $missionName);
        if (count($temp) > 1) {
            return false;
        }
        $array = array();
        $result = DB::select('SELECT * FROM mission WHERE id != ? AND user= ?', array($id, $user));
        foreach ($result as $result)
        {
            $array[] = $result->mission_name;
        }
        $result2 = DB::select('SELECT COUNT(id) AS num FROM mission WHERE id != ? AND user=? AND (mission_name= ? OR mission_name LIKE ? )',
            array($id, $user, $missionName, $missionName.'(%'));
        $count_name = $result2[0]->num;
        if ($count_name > 0) {
            $result3 = DB::select('SELECT * FROM mission WHERE id = ? AND user=?', array($id, $user));
            $name = $result3[0]->mission_name;
            $newMissionName = $missionName.'('.$count_name.')';
            for ($i = 0; $i< count($array); $i++) {
                if ($array[$i] == $newMissionName) {
                    --$count_name;
                    $new_mission_name = $missionName.'('.$count_name.')';
                    $i =0;
                }
            }
            if ($count_name == 0)  $newMissionName = $missionName;
            DB::update('UPDATE mission SET mission_name = ? WHERE id = ?', array($newMissionName, $id));
            DB::update('UPDATE project SET mission_name = ? WHERE mission_name = ? AND user= ?', array($newMissionName, $name, $user));
            return $new_mission_name;
        }else{
            $result3 = DB::select('SELECT * FROM mission WHERE id = ?',array($id));
            $name = $result3[0]->mission_name;
            DB::update('UPDATE mission SET mission_name = ? WHERE id = ?', array($missionName,$id));
            DB::update('UPDATE project SET mission_name = ? WHERE mission_name = ? AND user= ?', array($missionName, $name, $user));
            return $missionName;
        }
    }

    public function find($user, $session)
    {
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
        for ($i = 0; $i < $num; $i++){

                if ($session != $user ) {
                    if ($result[$i]->collaborators != "public") {
                        if ($result[$i]->collaborators == "private") {
                            unset($result[$i]);
                            continue;
                        }
                        $userArray = explode(',',$result[$i]->collaborators);
                        $numarray = count($userArray);
                        $temp = true;
                        for ($q = 0; $q < $numarray; $q++){
                                if ($userArray[$q] == $session) {
                                    $temp = false;
                                }
                        }
                        if ($temp) {
                            unset($result[$i]);
                            continue;
                        }
                    }
                }

        }
        foreach ($result as $result) {
            $list['result'][] = $result;
        }
        return $list;
    }

    public function findImg($user, $session, $page)
    {
        $PER_PAGE = 10;
        $list = array(
            'num_page'   => 1,
            'result'     => array(),
            'numImg'     =>array()
        );
        $missionArray = array();
        $result = array();
        $noImg = "/picture/no_image.gif";
        $results = DB::select('SELECT * FROM mission where user = ?  ORDER BY id DESC', array($user));
        foreach ($results as $results) {
            $result[] = $results;
        }
        $num = count($result);
        for ($i = 0 ;$i < $num; $i++) {
                if ($session != $user ) {
                    if ($result[$i]->collaborators != "public") {
                        if ($result[$i]->collaborators == "private") {
                            unset($result[$i]);
                            continue;
                        }
                        $userArray = explode(',', $result[$i]->collaborators);
                        $numArray = count($userArray);
                        $temp = true;
                        for ($q=0; $q < $numArray; $q++) {
                            if ($userArray[$q] == $session) {
                                $temp = false;
                            }
                        }
                        if ($temp) {
                            unset($result[$i]);
                            continue;
                        }
                    }
                }
        }
        foreach ($result as $result) {
            $missionArray[] = $result;
        }
        $num = count($missionArray);            /* num: number of project */
        $list['num_page'] = ceil($num / $PER_PAGE);
        $start_page = ($page-1)*$PER_PAGE+1;
        if ($num > $page*$PER_PAGE) {
            $end_page = $page*$PER_PAGE;
        } else {
            $end_page = $num;
        }
        for ($i = $start_page-1 ;$i < $end_page; $i++){
            $projectArray = array();
            $results2     = DB::select('SELECT * FROM project where user = ?  AND BINARY mission_name= ?', array($user, $missionArray[$i]->mission_name));
            $result2      = DB::select('SELECT COUNT(id_pro) AS numImg FROM project WHERE BINARY mission_name = ?', array($missionArray[$i]->mission_name));
            foreach ($results2 as $results2)
            {
                $projectArray[] = $results2->id_pro;
            }
            if ($projectArray == null ) {
                $arrayTemp = array(
                    'mission'       => $missionArray[$i]->mission_name,
                    'img'           => $noImg,
                    'id'            => $missionArray[$i]->id,
                    'collaborators' => (string)$missionArray[$i]->collaborators,
                    'num_img'       =>  $result2[0]->numImg
                );
                $list['result'][] = $arrayTemp;
                continue;
            }
            $num2 = count($projectArray);
            $projectId = 0;
            for ($j = 0 ; $j < $num2 ; $j++) {
                if($projectId < $projectArray[$j])
                    $projectId = $projectArray[$j];
            }
            $results3 = DB::select('SELECT * FROM project where user = ?  AND id_pro= ?', array($user, $projectId));
            $arrayTemp = array(
                'mission'       => $missionArray[$i]->mission_name,
                'img'           =>  $results3[0]->url_square,
                'id'            => $missionArray[$i]->id,
                'collaborators' => (string)$missionArray[$i]->collaborators,
                'num_img'       =>  $result2[0]->numImg
            );
            $list['result'][] = $arrayTemp;
        }
        return $list;
    }
}