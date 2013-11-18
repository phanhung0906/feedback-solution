<?php
Class ProjectController extends Controller{
    protected $projectModel;

    public function __construct(){
        $this->projectModel = new ProjectModel;
    }

    function index(){
        return View::make('project.project');
    }

     function addProject(){
        $user = $_POST['user'];
        echo $this->projectModel->addProject($user);
    }

     function deleteProject(){
        $id = $_POST['id'];
        echo $this->projectModel->deleteProject($id);
    }

     function editProject(){
        $id = $_POST['id'];
        $mission_name = $_POST['mission_name'];
        echo $this->projectModel->editProject($id,$mission_name);
    }

     function listProject(){
        $user = $_POST['user'];
        echo $this->projectModel->listProject($user);
     }

     function ProjectImg(){
        $user=$_POST['user'];
        echo $this->projectModel->ProjectImg($user);
    }
}