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
         $get = $_GET['project'];
         $newget = str_replace("-"," ",$get);
         $get2    = $_GET['id_pro'];
         $newget2  = str_replace("-", " ", $get2);
         $list    = $this->projectModel->find($user, $session);
         $data    = array(
                        'user'    => $user,
                        'session' => $session,
                        'project' => $list['result'],
                        'get'     => $get,
                        'newget'  => $newget,
                        'newget2' => $newget2
                    );
         return View::make('design.design',$data);
     }

    public function listAction()
    {
        $user    = $_POST['user'];
        $name    = $_POST['name'];
        $mission = $_POST['mission'];
        echo $this->designModel->find($name, $mission, $user);
    }
}
