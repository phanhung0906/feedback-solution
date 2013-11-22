<?php
Class CommentController extends Controller
{
     protected $commentModel;

     public function __construct()
     {
         $this->commentModel = new CommentModel();
     }

    public function addAction()
    {
         $user   = $_POST['user'];
         $idProject = $_POST['id_pro'];
         $cmt    = $_POST['cmt'];
         $x      = $_POST['x'];
         $y      = $_POST['y'];
         echo $this->commentModel->add($user, $idProject, $cmt, $x, $y);
    }

    public function deleteAction()
    {
        $id = $_POST['id'];
        echo $this->commentModel->delete($id);
    }

    public function editAction()
    {
        $id = $_POST['id'];
        $newComment = $_POST['new_comment'];
        echo $this->commentModel->edit($id, $newComment);
    }

    public function listAction()
    {
        $id = $_POST['id_btn'];
        echo $this->commentModel->find($id);
    }

}