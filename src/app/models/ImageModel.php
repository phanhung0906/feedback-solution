<?php
Class ImageModel
{

    public function upload($user, $url_original, $url_square, $mission, $name, $size)
    {
        DB::insert('INSERT INTO project (user,content,url_square,mission_name,name,size) values (?, ?,?, ?,?, ?)',
            array($user, $url_original, $url_square, $mission, $name, $size));
        return mysql_insert_id();
    }

    public function delete($id_pro)
    {
        $image = array(
            'image'=>array()
        );
        $result = DB::select('SELECT * FROM project WHERE id_pro= ?', array($id_pro));
        foreach ($result as $result) {
            $image['image'] = $result;
        }
        $path_content = explode("/", $image['image']->content);
        $path_square = explode("/", $image['image']->url_square);
        unlink(IMAGES_DIR.'/'.$path_content[3].'/'.$path_content[4].'/'.$path_content[5].'/'.$path_content[6]);
        unlink(IMAGES_DIR.'/'.$path_square[3].'/'.$path_square[4].'/'.$path_square[5].'/'.$path_square[6]);
        DB::delete('DELETE FROM project WHERE id_pro= ?', array($id_pro));
        DB::delete('DELETE FROM button WHERE id_pro= ?', array($id_pro));
        DB::delete('DELETE FROM comment WHERE id_pro= ?', array($id_pro));
        return true;
    }

    public function edit($id_pro, $name, $user, $mission)
    {
        $temp = explode('(',$name);
        if (count($temp) > 1)
            return false;
        $array1 = array();
        $result = DB::select('SELECT * FROM project WHERE id_pro != ? AND user = ? AND mission_name = ?', array($id_pro, $user, $mission));
        foreach ($result as $result){
            $array1[] = $result->name;
        }
        $result2 = DB::select('SELECT COUNT(id_pro) AS num FROM project WHERE id_pro != ? AND user = ? AND mission_name = ? AND (name = ? OR name LIKE ? )',
            array($id_pro, $user, $mission, $name, $name.'(%'));
        $count_name = $result2[0]->num;
        if ($count_name > 0) {
            $new_name = $name.'('.$count_name.')';
            $count_array1 =  count($array1);
            for ($i=0 ; $i < $count_array1; $i++) {
                if ($array1[$i] == $new_name) {
                    --$count_name;
                    $new_name = $name.'('.$count_name.')';
                    $i =0;
                }
            }
            if ($count_name == 0)
                $new_name = $name;
            DB::update('UPDATE project SET name= ? WHERE id_pro = ?', array($new_name, $id_pro));
            return $new_name;
        }
        $array = array();
        $result3 = DB::select('SELECT * FROM project WHERE user = ? AND mission_name = ?',array($user, $mission));
        foreach ($result3 as $result3) {
            $array[] = $result3->name;
        }
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i] == $name ) {
                return $name;
            }
        }
        DB::update('UPDATE project SET name = ? WHERE id_pro = ?', array($name, $id_pro));
        return $name;
    }

    public function find($user, $mission, $page)
    {
        $PER_PAGE = 15;
        $list = array(
            'result'     => array(),
            'cmt'       => array(),
            'num_page'  => 1,
        );
        $num_page_result = DB::select('SELECT COUNT(id_pro) AS total FROM project WHERE user = ? AND BINARY mission_name= ?',
            array($user, $mission));
        $num_page = $num_page_result[0]->total;
        $result = DB::select('SELECT * FROM project WHERE user = ? AND BINARY mission_name= ? ORDER BY id_pro DESC LIMIT ?,? ',
            array($user, $mission, ($page-1)*$PER_PAGE, $PER_PAGE));
        foreach ($result as $result) {
            $list['result'][] = $result;
        }
        $num = count($list['result']);
        for ($i = 0 ; $i < $num; $i++) {
            $result2 = DB::select('SELECT COUNT(id_pro) AS numcomment FROM button WHERE id_pro= ? ', array($list['result'][$i]->id_pro));
            foreach ($result2 as $result2) {
                $list['cmt'][] = $result2;
            }
        }
        $list['num_page'] = ceil($num_page/$PER_PAGE);
        return $list;
    }
}