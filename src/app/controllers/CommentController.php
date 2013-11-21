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
         $id_pro = $_POST['id_pro'];
         $cmt    = $_POST['cmt'];
         $x      = $_POST['x'];
         $y      = $_POST['y'];
         echo $this->commentModel->add($user, $id_pro, $cmt, $x, $y);
    }

    public function deleteAction()
    {
        $id = $_POST['id'];
        echo $this->commentModel->delete($id);
    }

    public function editAction()
    {
        $id = $_POST['id'];
        echo $this->commentModel->edit($id);
    }

    public function listAction()
    {
        $id_btn = $_POST['id_btn'];
        echo $this->commentModel->find($id_btn);
    }

}