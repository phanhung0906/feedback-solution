<?php
Class ButtonController extends Controller
{
    protected $buttonModel;

    public function __construct()
    {
         $this->buttonModel = new ButtonModel();
     }

    public function checkAction()
    {
         $id_btn = $_POST['id_btn'];
         echo $this->buttonModel->check($id_btn);
    }

    public function deleteAction()
    {
        $id_btn =$_POST['id_btn'];
        echo $this->buttonModel->delete($id_btn);
    }
}