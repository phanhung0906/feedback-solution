<?php
Class ButtonModel extends Eloquent{
    function checkDeleteButton($id_btn){
        $array=array();
        $result = DB::select('SELECT * FROM comment WHERE id_btn= ?',array($id_btn));
        foreach($result as $result){
            $array[] = $result;
        }
        if($array == null){
            DB::delete('DELETE FROM button WHERE id_btn= ?',array($id_btn));
            return "OK";
        }else return "undelete";
    }

    function deleteButton($id_btn){
        DB::delete('DELETE FROM comment WHERE id_btn= ?',array($id_btn));
        DB::delete('DELETE FROM button WHERE id_btn= ?',array($id_btn));
        return "OK";
    }
}