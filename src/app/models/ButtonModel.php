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
}