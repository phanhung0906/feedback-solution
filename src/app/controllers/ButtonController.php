<?php

class ButtonController extends Controller
{
    protected $buttonModel;

    public function __construct()
    {
         $this->buttonModel = new ButtonModel();
    }

    public function checkAction()
    {
         $id = $_POST['id_btn'];
         echo $this->buttonModel->check($id);
    }

    public function deleteAction()
    {
        $id = $_POST['id_btn'];
        echo $this->buttonModel->delete($id);
    }

    public function listAction()
    {
        $user    = $_POST['user'];
        $name    = $_POST['name'];
        $mission = $_POST['mission'];
        echo $this->buttonModel->find($name, $mission, $user);
    }
}