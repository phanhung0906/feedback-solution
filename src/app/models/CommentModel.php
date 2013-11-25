<?php

class CommentModel
{

    public function add($user, $idProject, $cmt, $x, $y)
    {
        $count = 0;
        $array = array();
        $result = DB::select('SELECT * FROM button');
        foreach ($result as $result) {
            $array[] = $result;
            if (($result->x == $x) && ($result->y == $y)) {
                $idButton = $result->id_btn;
            } else {
                ++$count;
            }
        }

        if (count($array) == $count) {
            $id = DB::table('button')->insertGetId(
                array('x' => $x, 'y' => $y, 'id_pro' => $idProject)
            );
            $idButton = $id;
        }
        $id2 = DB::table('comment')->insertGetId(
            array('user' => $user, 'id_pro' => $idProject, 'comment' => $cmt, 'id_btn' => $idButton, 'x' => $x, 'y' => $y)
        );
        return  $id2;
    }

    public function delete($id)
    {
        DB::delete('DELETE FROM comment WHERE id= ?', array($id));
        return true;
    }

    public function edit($id, $newComment)
    {
        DB::update('UPDATE comment SET comment= ? WHERE id= ?', array($newComment,$id));
        return true;
    }

    public function find($id)
    {
        $list = array(
            'result'     => array(),
        );
        $result = DB::select('SELECT * FROM comment WHERE id_btn= ?', array($id));
        foreach ($result as $result) {
            $list['result'][] = $result;
        }
        return json_encode($list);
    }
}