<?php

    Route::get('/', function()
    {
        if (!Session::has('user')) {
            return View::make('index')->with('error', '');
        }
        return Redirect::to('/'.Session::get('user').'/page/1');
    });

    Route::get('/login-facebook','FacebookController@login');

    Route::get('/login-facebook/callback','FacebookController@callback');

    Route::filter('check-user', 'UserController@filter');

    if (!Session::has('user')) {
        Route::post('/password/forgot', 'UserController@forgotPassword');
        Route::post('/{user}/{project}/page/{num_page}', 'UserController@postLogin');
        Route::post('/{user}/{project}/{id_pro}', 'UserController@postLogin');
        Route::post('/{user}/page/{num_page}', 'UserController@postLogin');
        Route::get('/password/reset/{email}/{token}', 'UserController@receiveMail');
        Route::post('/password/reset/{email}/{token}', 'UserController@sendMail');
    }
    Route::group(array('before' => 'check-user'), function()
    {
       # For ajax
        Route::post('/project/edit', 'ProjectController@editAction');
        Route::post('/project/delete', 'ProjectController@deleteAction');
        Route::post('/project/add', 'ProjectController@addAction');
        Route::post('/image/upload', 'ImageController@uploadAction');
        Route::post('/image/edit', 'ImageController@editAction');
        Route::post('/image/delete', 'ImageController@deleteAction');
        Route::post('/design/list', 'DesignController@listAction');
        Route::post('/comment/add', 'CommentController@addAction');
        Route::post('/comment/delete', 'CommentController@deleteAction');
        Route::post('/comment/edit', 'CommentController@editAction');
        Route::post('/comment/notify','UserController@notify');

        Route::post('/button/check', 'ButtonController@checkAction');
        Route::post('/button/delete', 'ButtonController@deleteAction');

        Route::post('/collaborator/add', 'CollaboratorController@addAction');
        Route::post('/collaborator/delete', 'CollaboratorController@deleteAction');
        Route::post('/collaborator/list', 'CollaboratorController@listAction');
        Route::post('/collaborator/listuser', 'CollaboratorController@listUserAction');

        # Load page
        Route::get('setting/upload/{project}', 'ImageController@addAction');
        Route::get('/{user}/{project}/collaboration', 'CollaboratorController@indexAction');
        Route::post('setting/password', 'UserController@changeAction');
        Route::get('setting/{action}', 'ProjectController@setAction');
        Route::get('/logout', 'UserController@logoutAction');
    });
    # Load page
    Route::post('/comment/list', 'CommentController@listAction');
    Route::post('/button/list', 'ButtonController@listAction');
    Route::get('/{user}/{project}/page/{num_page}', 'ImageController@indexAction');
    Route::get('/{user}/page/{num_page}', 'ProjectController@indexAction');
    Route::get('/{user}/{project}/{id_pro}', 'DesignController@indexAction');
    if (!Session::has('user')) {
        Route::controller('/', 'UserController');
    }