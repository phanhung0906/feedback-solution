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
         Resque::setBackend('localhost:6379');
         $args = array(
             'name' => 'Chris'
         );
         Resque::enqueue('default', 'My_Job', $args);

         $user    = $_GET['user'];
         $session = Session::get('user');
         $get     = $_GET['project'];
         $newget  = str_replace("-", " ", $get);
         $get2    = $_GET['id_pro'];
         $newget2 = str_replace("-", " ", $get2);
         $list    = $this->projectModel->find($user, $session);
         $image   = $this->designModel->find($newget2, $newget, $user);
         $check = false;
         $num = count($list['result']);
         for ($i = 0; $i < $num; $i++) {
             if ($list['result'][$i]->mission_name == $newget) {
                 $check = true;
             }
         }
         if($check == false) return View::make('error.collaborator');
         $data    = array(
                        'user'    => $user,
                        'session' => $session,
                        'get'     => $get,
                        'newget'  => $newget,
                        'newget2' => $newget2,
                        'project'  => $list['result'],
                        'image'   => $image
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
