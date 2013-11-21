<?php
Class ProjectController extends Controller{
    protected $projectModel;

    public function __construct(){
        $this->projectModel = new ProjectModel;
    }

    function index(){
        $user = $_GET['user'];
        $page = $_GET['page'];
        $data = $this->projectModel->listProject($user);
        $project = $this->projectModel->ProjectImg($user,$page);
        return View::make('project.project')->with('project',$data['result'])->with('projectImg',$project['result'])->with('num_page',$project['num_page']);

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
        $user = $_POST['user'];
        echo $this->projectModel->editProject($id,$mission_name,$user);
    }

//     function listProject(){
//        $user = $_POST['user'];
//        echo $this->projectModel->listProject($user);
//     }

//     function ProjectImg(){
//        $user=$_POST['user'];
//        echo $this->projectModel->ProjectImg($user);
//    }
}