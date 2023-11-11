<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
    return $router->app->version();
});

$router->group(['prefix' => 'article'], function ($router) {
    $router->get('/', 'Article@all');
    $router->get('/pagination', 'Article@dataPagination');
    $router->get('/{id}', 'Article@show');
    $router->post('/', 'Article@create');
    $router->put('/{id}', 'Article@update');
    $router->delete('/{id}', 'Article@hapus');
});
$router->group(['prefix' => 'articleAtt'], function ($router) {
    $router->get('/', 'ArticleAttachment@all');
    $router->get('/pagination', 'ArticleAttachment@dataPagination');
    $router->get('/{id}', 'ArticleAttachment@show');
    $router->post('/', 'ArticleAttachment@create');
    $router->put('/{id}', 'ArticleAttachment@update');
    $router->delete('/{id}', 'ArticleAttachment@hapus');
});
$router->group(['prefix' => 'articleCat'], function ($router) {
    $router->get('/', 'ArticleCategory@all'); // tidak berpengaruh asyncnya
    $router->get('/sync', 'ArticleCategory@allSync');
    $router->get('/syncreact', 'ArticleCategory@allReactPhp');
    $router->get('/pagination', 'ArticleCategory@dataPagination');
    $router->get('/{id}', 'ArticleCategory@show');
    $router->post('/', 'ArticleCategory@create');
    $router->put('/{id}', 'ArticleCategory@update');
    $router->delete('/{id}', 'ArticleCategory@hapus');
});
$router->group(['prefix' => 'forms'], function ($router) {
    $router->get('/', 'FormioForms@all');
    $router->get('/pagination', 'FormioForms@dataPagination');
    $router->get('/{id}', 'FormioForms@show');
    $router->post('/', 'FormioForms@create');
    $router->put('/{id}', 'FormioForms@update');
    $router->delete('/{id}', 'FormioForms@hapus');
});
$router->group(['prefix' => 'formsSubmission'], function ($router) {
    $router->get('/', 'FormioSubmission@all');
    $router->get('/pagination', 'FormioSubmission@dataPagination');
    $router->get('/{id}', 'FormioSubmission@show');
    $router->post('/', 'FormioSubmission@create');
    $router->put('/{id}', 'FormioSubmission@update');
    $router->delete('/{id}', 'FormioSubmission@hapus');
});
$router->group(['prefix' => 'rbacAssign'], function ($router) {
    $router->get('/', 'RbacAuthAssignment@all');
    $router->get('/pagination', 'RbacAuthAssignment@dataPagination');
    $router->get('/{user_id}', 'RbacAuthAssignment@show');
    $router->post('/', 'RbacAuthAssignment@create');
    $router->put('/{user_id}', 'RbacAuthAssignment@update');
    $router->delete('/{user_id}', 'RbacAuthAssignment@hapus');
});
$router->group(['prefix' => 'rbacItem'], function ($router) {
    $router->get('/', 'RbacAuthItem@all');
    $router->get('/pagination', 'RbacAuthItem@dataPagination');
    $router->get('/{name}', 'RbacAuthItem@show');
    $router->post('/', 'RbacAuthItem@create');
    $router->put('/{name}', 'RbacAuthItem@update');
    $router->delete('/{name}', 'RbacAuthItem@hapus');
});
$router->group(['prefix' => 'rbacChild'], function ($router) {
    $router->get('/', 'RbacAuthItemChild@all');
    $router->get('/{child}', 'RbacAuthItemChild@show');
    $router->post('/', 'RbacAuthItemChild@create');
    $router->put('/{child}', 'RbacAuthItemChild@update');
    $router->delete('/{child}', 'RbacAuthItemChild@hapus');
});
$router->group(['prefix' => 'rbacRule'], function ($router) {
    $router->get('/', 'RbacAuthRule@all');
    $router->get('/{name}', 'RbacAuthRule@show');
    $router->post('/', 'RbacAuthRule@create');
    $router->put('/{name}', 'RbacAuthRule@update');
    $router->delete('/{name}', 'RbacAuthRule@hapus');
});
$router->group(['prefix' => 'fileSystem'], function ($router) {
    $router->get('/', 'FileStorageItem@all');
});