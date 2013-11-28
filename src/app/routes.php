<?php

    Route::get('/', function()
    {
        if (!Session::has('user')) {
            return View::make('index');
        }
        return Redirect::to('/'.Session::get('user').'/page/1');
    });

    Route::filter('check-user',function()
    {
        if (!Session::has('user')) {
            return Redirect::to('login');
        }
    });

    if (!Session::has('user')) {
        Route::controller('/', 'UserController');
    }

    Route::group(array('before' => 'check-user'), function()
    {
       # For ajax
       # Route::post('listproject','ProjectController@listProject');
       # Route::post('ProjectImg','ProjectController@ProjectImg');
        Route::post('/project/edit', 'ProjectController@editAction');
        Route::post('/project/delete', 'ProjectController@deleteAction');
        Route::post('/project/add', 'ProjectController@addAction');
        Route::post('/image/upload', 'ImageController@uploadAction');
       # Route::post('listImage','ImageController@listImage');
        Route::post('/image/edit', 'ImageController@editAction');
        Route::post('/image/delete', 'ImageController@deleteAction');
        Route::post('/design/list', 'DesignController@listAction');
        Route::post('/comment/add', 'CommentController@addAction');
        Route::post('/comment/delete', 'CommentController@deleteAction');
        Route::post('/comment/edit', 'CommentController@editAction');
        Route::post('/comment/list', 'CommentController@listAction');
        Route::post('/button/check', 'ButtonController@checkAction');
        Route::post('/button/delete', 'ButtonController@deleteAction');
        Route::post('/collaborator/add', 'CollaboratorController@addAction');
        Route::post('/collaborator/delete', 'CollaboratorController@deleteAction');
        Route::post('/collaborator/list', 'CollaboratorController@listAction');
        Route::post('/collaborator/listuser', 'CollaboratorController@listUserAction');

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
