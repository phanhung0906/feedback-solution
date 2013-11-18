<?php
Class ButtonController extends Controller{
    protected $buttonModel;

     function __construct(){
         $this->buttonModel = new ButtonModel();
     }

     function checkDeleteButton(){
         $id_btn = $_POST['id_btn'];
         echo $this->buttonModel->checkDeleteButton($id_btn);
    }

     function deleteButton(){
        $id_btn =$_POST['id_btn'];
        echo $this->buttonModel->deleteButton($id_btn);
    }

}