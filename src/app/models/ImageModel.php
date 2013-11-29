<?php

class ImageModel
{

    public function upload($user, $urlOriginal, $urlSquare, $mission, $name, $size)
    {
        DB::insert('INSERT INTO project (user,content,url_square,mission_name,name,size) values (?, ?,?, ?,?, ?)',
            array($user, $urlOriginal, $urlSquare, $mission, $name, $size));
        return true;
    }

    public function delete($id)
    {
        $image = array(
            'image' => array()
        );
        $result = DB::select('SELECT * FROM project WHERE id_pro= ?', array($id));
        foreach ($result as $result) {
            $image['image'] = $result;
        }
        $pathContent = explode("/", $image['image']->content);
        $pathSquare = explode("/", $image['image']->url_square);
        unlink(IMAGES_DIR . '/' . $pathContent[3] . '/' . $pathContent[4] . '/' . $pathContent[5] . '/' . $pathContent[6]);
        unlink(IMAGES_DIR . '/' . $pathSquare[3] . '/' . $pathSquare[4] . '/' . $pathSquare[5] . '/' . $pathSquare[6]);
        DB::delete('DELETE FROM project WHERE id_pro= ?', array($id));
        DB::delete('DELETE FROM button WHERE id_pro= ?', array($id));
        DB::delete('DELETE FROM comment WHERE id_pro= ?', array($id));
        return true;
    }

    public function edit($id, $name, $user, $mission)
    {
        $temp = explode('(',$name);
        if (count($temp) > 1) {
            return false;
		}
        $array1 = array();
        $result = DB::select('SELECT * FROM project WHERE id_pro != ? AND user = ? AND BINARY mission_name = ?', array($id, $user, $mission));
        foreach ($result as $result){
            $array1[] = $result->name;
        }
        $result2 = DB::select('SELECT COUNT(id_pro) AS num FROM project WHERE id_pro != ? AND user = ? AND BINARY mission_name = ? AND (BINARY name = ? OR BINARY name LIKE ? )',
            array($id, $user, $mission, $name, $name.'(%'));
        $countName = $result2[0]->num;
        if ($countName > 0) {
            $newName = $name.'('.$countName.')';
            $count_array1 =  count($array1);
            for ($i=0 ; $i < $count_array1; $i++) {
                if ($array1[$i] == $newName) {
                    --$countName;
                    $newName = $name.'('.$countName.')';
                    $i =0;
                }
            }
            if ($countName == 0) {
                $newName = $name;
			}
            DB::update('UPDATE project SET name = ? WHERE id_pro = ?', array($newName, $id));
            return $newName;
        }
        $array = array();
        $result3 = DB::select('SELECT * FROM project WHERE user = ? AND BINARY mission_name = ?',array($user, $mission));
        foreach ($result3 as $result3) {
            $array[] = $result3->name;
        }
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] == $name ) {
                return $name;
            }
        }
        DB::update('UPDATE project SET name = ? WHERE id_pro = ?', array($name, $id));
        return $name;
    }

    public function find($user, $mission, $page)
    {
        $PER_PAGE = 15;
        $list = array(
            'result'    => array(),
            'cmt'       => array(),
            'num_page'  => 1,
        );
        $num_page_result = DB::select('SELECT COUNT(id_pro) AS total FROM project WHERE user = ? AND BINARY mission_name= ?',
            array($user, $mission));
        $num_page = $num_page_result[0]->total;
        $result = DB::select('SELECT * FROM project WHERE user = ? AND BINARY mission_name= ? ORDER BY id_pro DESC LIMIT ?,?',
            array($user, $mission, ($page-1)*$PER_PAGE, $PER_PAGE));
        foreach ($result as $result) {
            $list['result'][] = $result;
        }
        $num = count($list['result']);
        for ($i = 0 ; $i < $num; $i++) {
            $result2 = DB::select('SELECT COUNT(id_pro) AS numcomment FROM button WHERE id_pro= ?', array($list['result'][$i]->id_pro));
            foreach ($result2 as $result2) {
                $list['cmt'][] = $result2;
            }
        }
        $list['num_page'] = ceil($num_page/$PER_PAGE);
        return $list;
    }
}