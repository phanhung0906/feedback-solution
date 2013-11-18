<?php
Class CommentController extends Controller{
     protected $commentModel;

     function __construct(){
         $this->commentModel = new CommentModel();
     }

     function addComment(){
         $user   = $_POST['user'];
         $id_pro = $_POST['id_pro'];
         $cmt    = $_POST['cmt'];
         $x      = $_POST['x'];
         $y      = $_POST['y'];
        echo $this->commentModel->addComment($user,$id_pro,$cmt,$x,$y);
    }

     function deleteComment(){
        $id = $_POST['id'];
        echo $this->commentModel->deleteComment($id);
    }

     function editComment(){
        $id = $_POST['id'];
        echo $this->commentModel->editComment($id);
    }

     function listComment(){
        $id_btn = $_POST['id_btn'];
        echo $this->commentModel->listComment($id_btn);
    }

}