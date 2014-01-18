<?php

class ProjectController extends Controller
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
        $list    = $this->projectModel->find($user, $session);
        $project = $this->projectModel->findImg($user, $session, $page);
        $data    = array(
                        'project'    => $list['result'],
                        'projectImg' => $project['result'],
                        'num_page'   => $project['num_page'],
                        'user'       => $user,
                        'session'    => $session,
                        'page'       => $page
                    );
        return View::make('project.project',$data);
    }

    public function addAction()

    {
        $user        = $_POST['user'];
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
        $id          = $_POST['id'];
        $missionName = $_POST['mission_name'];
        $user        = $_POST['user'];
        echo $this->projectModel->edit($id, $missionName, $user);
    }

    public function setAction($action)
    {
        $user         = Session::get('user');
        $session      = Session::get('user');
        $list         =  $this->projectModel->find($user, $session);
        $data         = array(
            'user'        => $user,
            'session'     => $session,
            'error'       => '',
            'project'     => $list['result'],
        );
        return View::make('user.'.$action,$data);
    }
}