<?php

    Route::get('/', function()
    {
        if (!Session::has('user')) {
            return View::make('index');
        } else return Redirect::to('/'.Session::get('user').'/page/1');
    });

    Route::filter('check-user',function()
    {
        if (!Session::has('user'))
            return Redirect::to('login');
    });

    if (!Session::has('user')) {
        Route::controller('/', 'UserController');
    }

    Route::group(array('before'=>'check-user'), function()
    {
       # For ajax
       # Route::post('listproject','ProjectController@listProject');
       # Route::post('ProjectImg','ProjectController@ProjectImg');
        Route::post('editProject', 'ProjectController@editAction');
        Route::post('deleteProject', 'ProjectController@deleteAction');
        Route::post('addProject', 'ProjectController@addAction');
        Route::post('upload', 'ImageController@uploadAction');
       # Route::post('listImage','ImageController@listImage');
        Route::post('editImage', 'ImageController@editAction');
        Route::post('deleteImage', 'ImageController@deleteAction');
        Route::post('design', 'DesignController@listAction');
        Route::post('addComment', 'CommentController@addAction');
        Route::post('deleteComment', 'CommentController@deleteAction');
        Route::post('editComment', 'CommentController@editAction');
        Route::post('listComment', 'CommentController@listAction');
        Route::post('checkDeleteButton', 'ButtonController@checkAction');
        Route::post('deleteButton', 'ButtonController@deleteAction');
        Route::post('addCollaborator', 'CollaboratorController@addAction');
        Route::post('deleteCollaborator', 'CollaboratorController@deleteAction');
        Route::post('listCollaborator', 'CollaboratorController@listAction');
        Route::post('userCollaborator', 'CollaboratorController@listUserAction');

        # Load page
        Route::get('/{user}/{project}/page/{num_page}', 'ImageController@indexAction');
        Route::get('/{user}/{project}/collaboration', 'CollaboratorController@indexAction');
        Route::get('/{user}/page/{num_page}', 'ProjectController@indexAction');
        Route::get('setting/{action}', function($action)
        {
            $projectModel = new ProjectModel();
            $user         = Session::get('user');
            $session      = Session::get('user');
            $list         = $projectModel->find($user, $session);
            $data         = array(
                'user'        => $user,
                'session'     => $session,
                'error'       => '',
                'project'     => $list['result'],
            );
            return View::make('user.'.$action,$data);
        });
        Route::get('/{user}/{project}/{id_pro}', 'DesignController@indexAction');
        Route::get('/logout', 'UserController@logoutAction');
        Route::post('setting/password', 'UserController@changeAction');
    });
