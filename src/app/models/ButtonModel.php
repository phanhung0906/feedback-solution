<?php

class ButtonModel
{

    public function check($id)
    {
        $array_comment = array();
        $result = DB::select('SELECT * FROM comment WHERE id_btn= ?', array($id));
        foreach ($result as $result) {
            $array_comment[] = $result;
        }
        if ($array_comment == null) {
            DB::delete('DELETE FROM button WHERE id_btn= ?', array($id));
            return true;
        }
        return false;
    }

    public function delete($id)
    {
        DB::delete('DELETE FROM comment WHERE id_btn= ?', array($id));
        DB::delete('DELETE FROM button WHERE id_btn= ?', array($id));
        return true;
    }

    public function find($name, $mission, $user)
    {
        $list = array(
            'btn'       => array(),
        );
        $result = DB::select('SELECT * FROM project WHERE BINARY name = ? AND BINARY mission_name = ? AND user = ? ', array($name, $mission, $user));
        $id = $result[0]->id_pro;
        $result3 = DB::select('SELECT * FROM button WHERE id_pro = ?', array($id));
        foreach ($result3 as $result3) {
            $list['btn'][] = $result3;
        }
        return json_encode($list);
    }
}