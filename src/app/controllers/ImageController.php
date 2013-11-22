<?php
Class ImageController extends Controller
{
    protected $imageModel;
    protected $projecModel;

    public function __construct()
    {
        $this->imageModel = new ImageModel();
        $this->projectModel = new ProjectModel();
    }

    public function indexAction()
    {
        $get = $_GET['project_name'];
        $newget = str_replace("-", " ", $get);
        $user = $_GET['user'];
        $mission = $newget;
        $page = $_GET['page'];
        $data = $this->projectModel->find($user);
        $image = $this->imageModel->find($user, $mission, $page);
        return View::make('project.image')->with('project', $data['result'])->with('image', $image['result'])->with('numcmt', $image['cmt'])
            ->with('num_page', $image['num_page']);
    }

    public function uploadAction()
    {
         if (isset( $_FILES['file']['name'])) {
             $now = getdate();
             $uploaddir = IMAGES_DIR.'/'.Session::get('user');
             if (!is_dir( $uploaddir)) {
                 mkdir($uploaddir);
             }
             $uploaddir = $uploaddir.'/'.$now['year'];
             if(!is_dir( $uploaddir)) {
                 mkdir($uploaddir);
             }
             $uploaddir = $uploaddir.'/'.$now['mon'];
             if (!is_dir( $uploaddir)) {
                 mkdir($uploaddir);
             }
             $new_name = uniqid();
             $uploadfile = $uploaddir .'/'.$_FILES['file']['name'];
             $path_parts = pathinfo($uploadfile);
             $images_sq = $new_name.'_sq';
             $images_sq_url = $path_parts['dirname'].'/'.$images_sq.'.'.$path_parts['extension'];
             $uploadfile = $uploaddir .'/'. $new_name.'.'.$path_parts['extension'];
             if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
                 $image = new SimpleImageController();
                 $image->load($uploadfile);
                 $image->resize(160, 160);
                 $image->save($images_sq_url);
                 $user = Session::get('user');
                 $content =  $new_name.'.'.$path_parts['extension'];
                 $mission = $_POST['project'];
                 $size = $_POST['size'];
                 $images_sq = $images_sq.'.'.$path_parts['extension'];
                 $now = getdate();
                 $url_original= "/picture/uploads/".Session::get('user').'/'.$now['year'].'/'.$now['mon'].'/'.$content;
                 $url_square = "/picture/uploads/".Session::get('user').'/'.$now['year'].'/'.$now['mon'].'/'.$images_sq;
                 $arrayName = explode('.', $content);
                 $name = $arrayName[0];
                 echo $this->imageModel->upload($user, $url_original, $url_square, $mission, $name, $size);
             } else {
                 echo "False to upload file\n";
             }
         }
         echo 'False';
    }

    public function deleteAction()
    {
        $id = $_POST['id'];
        echo $this->imageModel->delete($id);
    }

    public function editAction()
    {
         $id = $_POST['id_pro'];
         $name = $_POST['name'];
         $user = $_POST['user'];
         $mission = $_POST['mission'];
         echo $this->imageModel->edit($id, $name, $user, $mission);
    }
}