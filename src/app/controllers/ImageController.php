<?php

class ImageController extends Controller
{
    const ERROR_EXIST_FILE = 'ERROR_EXIST_FILE';
    const ERROR_UPLOAD = 'ERROR_UPLOAD';

    protected $imageModel;
    protected $projecModel;

    public function __construct()
    {
        $this->imageModel   = new ImageModel();
        $this->projectModel = new ProjectModel();
    }

    public function indexAction()
    {
        $get     = $_GET['project_name'];
        $newget  = str_replace("-", " ", $get);
        $user    = $_GET['user'];
        $page    = $_GET['page'];
        $session = Session::get('user');
        $list    = $this->projectModel->find($user, $session);
        $image   = $this->imageModel->find($user, $newget, $page);
        $check = false;
        $num = count($list['result']);
        for ($i = 0; $i < $num; $i++) {
            if ($list['result'][$i]->mission_name == $newget) {
                $check = true;
            }
        }
        if($check == false) return View::make('error.collaborator');
        $data    = array(
                        'project'  => $list['result'],
                        'image'    => $image['result'],
                        'numcmt'   => $image['cmt'],
                        'num_page' => $image['num_page'],
                        'newget'   => $newget,
                        'get'      => $get,
                        'session'  => $session,
                        'user'     => $user,
                        'page'     => $page
                   );
        return View::make('images.image',$data);
    }

    public function uploadAction()
    {
         if ( isset( $_FILES['file']['name'])) {
             $now = getdate();
             $uploaddir = IMAGES_DIR . '/' . Session::get('user');
             if (!is_dir( $uploaddir)) {
                 mkdir($uploaddir);
             }
             $uploaddir = $uploaddir . '/' . $now['year'];
             if(!is_dir( $uploaddir)) {
                 mkdir($uploaddir);
             }
             $uploaddir = $uploaddir .'/' . $now['mon'];
             if (!is_dir( $uploaddir)) {
                 mkdir($uploaddir);
             }

             $post = explode(',', $_POST['size']);
             $num_files     =  count($_FILES['file']['name']);
             for($i = 0; $i < $num_files; $i++) {
                 $new_name      = uniqid();
                 $uploadfile    = $uploaddir . '/' . $_FILES['file']['name'][$i];
                 $path_parts    = pathinfo($uploadfile);
                 $images_sq     = $new_name . '_sq';
                 $images_sq_url = $path_parts['dirname'] . '/' . $images_sq . '.' . $path_parts['extension'];
                 $uploadfile = $uploaddir . '/' . $new_name . '.' . $path_parts['extension'];
                 if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {
                     $image = new SimpleImageController();
                     $image->load($uploadfile);
                     if ($image->getWidth() > $image->getHeight()) {
                         $image->resizeToWidth(160);
                     } else {
                         $image->resizeToHeight(160);
                     }
//                     $image->resize(160, 160);
                     $image->save($images_sq_url);
                     $user         = Session::get('user');
                     $content      = $new_name.'.'.$path_parts['extension'];
                     $mission      = $_POST['project'];
                     $size         = $post[$i];
                     $images_sq    = $images_sq.'.'.$path_parts['extension'];
                     $now 		   = getdate();
                     $url_original = "/picture/uploads/" . Session::get('user') . '/' . $now['year'] . '/' . $now['mon'] . '/' . $content;
                     $url_square   = "/picture/uploads/" . Session::get('user') . '/' . $now['year'] . '/' . $now['mon'] . '/' . $images_sq;
                     $arrayName    = explode('.', $content);
                     $name         = $arrayName[0];
                     echo $this->imageModel->upload($user, $url_original, $url_square, $mission, $name, $size);
                 } else {
                     echo ImageController::ERROR_UPLOAD;
                 }
             }
         }else{
             echo ImageController::ERROR_EXIST_FILE;
         }

    }

    public function deleteAction()
    {
        $id = $_POST['id'];
        echo $this->imageModel->delete($id);
    }

    public function editAction()
    {
         $id      = $_POST['id_pro'];
         $name    = $_POST['name'];
         $user 	  = $_POST['user'];
         $mission = $_POST['mission'];
         echo $this->imageModel->edit($id, $name, $user, $mission);
    }

    public function addAction($project)
    {
        $user         = Session::get('user');
        $session      = Session::get('user');
        $list         =  $this->projectModel->find($user, $session);
        $data         = array(
            'user'        => $user,
            'session'     => $session,
            'error'       => '',
            'project'     => $list['result'],
            'currentProject' => $project
        );
        //Check domain true or false
        $num = count($list['result']);
        $check = false;
        $newProject = str_replace("-", " ", $project);
        for ($i = 0; $i < $num; $i++) {
            if ($newProject == $list['result'][$i]->mission_name) {
               $check = true;
            }
        }
        if($check) {
            return View::make('user.upload',$data);
        }
    }
}