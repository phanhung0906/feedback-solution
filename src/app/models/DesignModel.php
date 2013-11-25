<?php

class DesignModel
{

    public function find($name, $mission, $user)
    {
        $list = array(
            'result'     => array(),
            'btn'       => array(),
        );
        $result = DB::select('SELECT * FROM project WHERE name= ? AND BINARY mission_name = ? AND user= ? ', array($name, $mission, $user));
        $id = $result[0]->id_pro;
        $result2 = DB::select('SELECT * FROM project WHERE id_pro= ? AND user= ?', array($id, $user));
        foreach ($result2 as $result2) {
            $list['result'][] = $result2;
        }
        $result3 = DB::select('SELECT * FROM button WHERE id_pro= ?', array($id));
        foreach ($result3 as $result3) {
            $list['btn'][] = $result3;
        }
        return json_encode($list);
    }
}