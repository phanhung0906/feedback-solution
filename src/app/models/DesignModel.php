<?php
Class DesignModel extends Eloquent{
    function design($name,$user){
        $list = array(
            'result'     => array(),
            'btn'       => array(),
        );
        $result = DB::select('SELECT * FROM project WHERE name= ?  AND user= ?',array($name,$user));
        $id = $result[0]->id_pro;
        $result2 = DB::select('SELECT * FROM project WHERE id_pro= ? AND user= ?',array($id,$user));
        foreach($result2 as $result2){
            $list['result'][] = $result2;
        }
        $result3 = DB::select('SELECT * FROM button WHERE id_pro= ?',array($id));
        foreach($result3 as $result3){
            $list['btn'][] = $result3;
        }
        return json_encode($list);
    }
}