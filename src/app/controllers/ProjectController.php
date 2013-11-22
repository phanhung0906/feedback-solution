<?php
Class ProjectController extends Controller
{
    protected $projectModel;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
    }

    public function indexAction()
    {
        $user    = $_GET['user'];
        $page    = $_GET['page'];
        $session = Session::get('user');
        $data    = $this->projectModel->find($user, $session);
        $project = $this->projectModel->findImg($user, $session, $page);
        return View::make('project.project')->with('project', $data['result'])->with('projectImg', $project['result'])->with('num_page', $project['num_page']);
    }

    public function addAction()
    {
        $user = $_POST['user'];
        $missionName = $_POST['missionName'];
        echo $this->projectModel->add($user, $missionName);
    }

    public function deleteAction()
    {
        $id = $_POST['id'];
        echo $this->projectModel->delete($id);
    }

    public function editAction()
    {
        $id = $_POST['id'];
        $missionName = $_POST['mission_name'];
        $user = $_POST['user'];
        echo $this->projectModel->edit($id, $missionName, $user);
    }
}