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
}