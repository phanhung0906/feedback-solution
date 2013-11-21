<?php
Class CollaboratorController extends Controller
{
    protected $collaboratorModel;
    protected $projectModel;

    public function __construct()
    {
        $this->collaboratorModel = new CollaboratorModel();
        $this->projectModel = new ProjectModel;
    }

    public function indexAction()
    {
        $user = $_GET['user'];
        $data = $this->projectModel->find($user);
        return View::make('user.collaborator')->with('error', '')->with('project', $data['result']);
    }

    public function addAction()
    {
         $mission_name = $_POST['mission_name'];
         $value = $_POST['value'];
         echo $this->collaboratorModel->add($mission_name, $value);
    }

    public function deleteAction()
    {
         $mission = $_POST['mission'];
         $user = $_POST['user'];
         echo $this->collaboratorModel->delete($mission, $user);
    }

    public function listAction()
    {
        $mission = $_POST['mission'];
        echo $this->collaboratorModel->find($mission);
    }

    public function listUserAction()
    {
        $user = $_POST['user'];
        $mission = $_POST['mission'];
        echo $this->collaboratorModel->findUser($user, $mission);
    }
}