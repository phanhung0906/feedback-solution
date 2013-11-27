<?php

class CollaboratorController extends Controller
{
    protected $collaboratorModel;
    protected $projectModel;

    public function __construct()
    {
        $this->collaboratorModel = new CollaboratorModel();
        $this->projectModel      = new ProjectModel();
    }

    public function indexAction()
    {
        $user        = $_GET['user'];
        $session     = Session::get('user');
        $get         = $_GET['project_name'];
        $newget      = str_replace("-"," ",$get);
        $list        = $this->projectModel->find($user, $session);
        $data        = array(
                        'user'        => $user,
                        'session'     => $session,
                        'error'       => '',
                        'project'     => $list['result'],
                        'get'         => $get,
                        'newget'      => $newget
                     );
        return View::make('user.collaborator',$data);
    }

    public function addAction()
    {
         $mission = $_POST['mission_name'];
         $value   = $_POST['value'];
         echo $this->collaboratorModel->add($mission, $value);
    }

    public function deleteAction()
    {
         $mission = $_POST['mission'];
         $user    = $_POST['user'];
         echo $this->collaboratorModel->delete($mission, $user);
    }

    public function listAction()
    {
        $mission = $_POST['mission'];
        echo $this->collaboratorModel->find($mission);
    }

    public function listUserAction()
    {
        $user    = $_POST['user'];
        $mission = $_POST['mission'];
        echo $this->collaboratorModel->findUser($user, $mission);
    }
}