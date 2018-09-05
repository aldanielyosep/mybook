<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    $lumen['version'] = $router->app->version();
    return json_encode($lumen);
});

$router->get('/connection', 'Test\TestController@connection');

$router->group(['middleware' => 'authHeader'], function () use ($router)
{ 
    $router->get('/category', 'Category\CategoryController@listCategory');
    $router->post('/category', 'Category\CategoryController@createCategory');
    $router->post('/categoryUpdate', 'Category\CategoryController@updateCategory');
    $router->post('/categoryDelete', 'Category\CategoryController@deleteCategory');
    // $router->put('/category', 'Category\CategoryController@updateCategory');
    // $router->delete('/category', 'Category\CategoryController@deleteCategory');
    $router->get('/book', 'Book\BookController@listBook');
    $router->post('/book', 'Book\BookController@createBook');
    $router->post('/bookUpdate', 'Book\BookController@updateBook');
    $router->post('/bookDelete', 'Book\BookController@deleteBook');
});
