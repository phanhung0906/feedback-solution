<?php

class DesignController extends Controller
{
     protected $designModel;
     protected $projectModel;

     public function __construct()
     {
         $this->designModel  = new DesignModel();
         $this->projectModel = new ProjectModel();
     }

     public function indexAction()
     {
         $user    = $_GET['user'];
         $session = Session::get('user');
         $data    = $this->projectModel->find($user, $session);
         return View::make('design.design')->with('project', $data['result']);
     }

    public function listAction()
    {
        $user    = $_POST['user'];
        $name    = $_POST['name'];
        $mission = $_POST['mission'];
        echo $this->designModel->find($name, $mission, $user);
    }
}
