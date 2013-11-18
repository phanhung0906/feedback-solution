<?php
Class CollaboratorController extends Controller{
    protected $collaboratorModel;

    function __construct(){
        $this->collaboratorModel = new CollaboratorModel();
    }

    function index(){
        return View::make('user.collaborator')->with('error','');
    }

     function addCollaborator(){
         $mission_name = $_POST['mission_name'];
         $value = $_POST['value'];
         echo $this->collaboratorModel->addCollaborator($mission_name,$value);
    }

     function deleteCollaborator(){
         $mission = $_POST['mission'];
         $user = $_POST['user'];
         echo $this->collaboratorModel->deleteCollaborator($mission,$user);
    }

     function listCollaborator(){
        $mission = $_POST['mission'];
        echo $this->collaboratorModel->listCollaborator($mission);
    }

     function userCollaborator(){
        $user = $_POST['user'];
        $mission = $_POST['mission'];
        echo $this->collaboratorModel->userCollaborator($user,$mission);
    }
}