<?php
Class ButtonModel
{
    public function check($id_btn)
    {
        $array_comment = array();
        $result = DB::select('SELECT * FROM comment WHERE id_btn= ?', array($id_btn));
        foreach ($result as $result) {
            $array_comment[] = $result;
        }
        if ($array_comment == null) {
            DB::delete('DELETE FROM button WHERE id_btn= ?', array($id_btn));
            return true;
        } else
            return false;
    }

    public function delete($id_btn)
    {
        DB::delete('DELETE FROM comment WHERE id_btn= ?', array($id_btn));
        DB::delete('DELETE FROM button WHERE id_btn= ?', array($id_btn));
        return true;
    }
}