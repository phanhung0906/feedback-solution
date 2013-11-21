<?php
Class CommentModel
{

    public function add($user, $id_pro, $cmt, $x, $y)
    {
        $count = 0;
        $array = array();
        $result = DB::select('SELECT * FROM button');
        foreach ($result as $result) {
            $array[] = $result;
            if (($result->x == $x) && ($result->y == $y)) {
                $id_btn = $result->id_btn;
            } else {
                ++$count;
            }
        }

        if (count($array) == $count) {
            $id = DB::table('button')->insertGetId(
                array('x' => $x, 'y' => $y, 'id_pro' => $id_pro)
            );
            $id_btn = $id;
        }
        $id2 = DB::table('comment')->insertGetId(
            array('user' => $user, 'id_pro' => $id_pro, 'comment' => $cmt, 'id_btn' => $id_btn, 'x' => $x, 'y' => $y)
        );
        return  $id2;
    }

    public function delete($id)
    {
        DB::delete('DELETE FROM comment WHERE id= ?', array($id));
        return true;
    }

    public function edit($id)
    {
        $new_comment = $_POST['new_comment'];
        DB::update('UPDATE comment SET comment= ? WHERE id= ?', array($new_comment,$id));
        return true;
    }

    public function find($id_btn)
    {
        $list = array(
            'result'     => array(),
        );
        $result = DB::select('SELECT * FROM comment WHERE id_btn= ?', array($id_btn));
        foreach ($result as $result) {
            $list['result'][] = $result;
        }
        return json_encode($list);
    }
}