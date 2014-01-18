<?php

class DesignModel
{

    public function find($name, $mission, $user)
    {
        $list = array(
            'result'    => array()
        );
        $result = DB::select('SELECT * FROM project WHERE BINARY name = ? AND BINARY mission_name = ? AND user = ? ', array($name, $mission, $user));
        foreach ($result as $result) {
            $list['result'][] = $result;
        }
        return $list;
    }
}