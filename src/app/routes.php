<?php

    Route::get('/', function()
    {
        if(!Session::has('user')){
        return View::make('index');
        }else return Redirect::to('/'.Session::get('user'));
    });
    Route::filter('check-user',function(){
        if(!Session::has('user'))
            return Redirect::to('login');
    });

//    Route::get('/login',function(){
//        if(!Session::has('user')){
//            return View::make('login');
//        } else return Redirect::to('/'.Session::get('user'));
//    });
//
//    Route::get('/register',function(){
//        if(!Session::has('user')){
//            return View::make('register');
//        }else return Redirect::to('/'.Session::get('user'));
//
//    });
    if(!Session::has('user')){
        Route::controller('/','UserController');
    }

    Route::group(array('before'=>'check-user'),function(){
        # For ajax
        Route::post('listproject','ProjectController@listProject');
        Route::post('ProjectImg','ProjectController@ProjectImg');
        Route::post('editProject','ProjectController@editProject');
        Route::post('deleteProject','ProjectController@deleteProject');
        Route::post('addProject','ProjectController@addProject');
        Route::post('upload','ImageController@upload');
        Route::post('listImage','ImageController@listImage');
        Route::post('editImage','ImageController@editImage');
        Route::post('deleteImage','ImageController@deleteImage');
        Route::post('design','DesignController@design');
        Route::post('addComment','CommentController@addComment');
        Route::post('deleteComment','CommentController@deleteComment');
        Route::post('editComment','CommentController@editComment');
        Route::post('listComment','CommentController@listComment');
        Route::post('checkDeleteButton','ButtonController@checkDeleteButton');
        Route::post('deleteButton','ButtonController@deleteButton');
        Route::post('addCollaborator','CollaboratorController@addCollaborator');
        Route::post('deleteCollaborator','CollaboratorController@deleteCollaborator');
        Route::post('listCollaborator','CollaboratorController@listCollaborator');
        Route::post('userCollaborator','CollaboratorController@userCollaborator');

        # Load page
        Route::get('/{user}/{project}/collaboration','CollaboratorController@index');
        Route::get('setting/{action}', function($action)
        {
            return View::make('user.'.$action)->with('error','');
        });
        Route::get('/{user}/{project}/{id_pro}','DesignController@index');
        Route::get('/{user}/{project}','ImageController@index');
        Route::get('/logout','UserController@logout');
        Route::get('/{user}','ProjectController@index');
        Route::post('setting/password','UserController@password');
    });
