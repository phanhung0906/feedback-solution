<?php
Class DesignController extends Controller{
     protected $designModel;

     function __construct(){
         $this->designModel = new DesignModel();
     }

     function index(){
         return View::make('design.design');
     }

     function design(){
        $user = $_POST['user'];
        $name = $_POST['name'];
        echo $this->designModel->design($name,$user);
    }
}
