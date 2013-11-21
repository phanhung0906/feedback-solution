<?php
Class DesignController extends Controller{
     protected $designModel;
     protected $projectModel;

     function __construct(){
         $this->designModel = new DesignModel();
         $this->projectModel = new ProjectModel;
     }

     function index(){
         $user = $_GET['user'];
         $data = $this->projectModel->listProject($user);
         return View::make('design.design')->with('project',$data['result']);
     }

     function design(){
        $user = $_POST['user'];
        $name = $_POST['name'];
        $mission = $_POST['mission'];
        echo $this->designModel->design($name,$mission,$user);
    }
}
