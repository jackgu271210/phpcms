<?php
// API路由

// API路由-新闻分类
use App\controllers\NewsController;

$router->map('GET', '/api/news', function() use ($pdo) {
   header('Content-Type: application/json');
   $controller = new NewsController($pdo);
   echo json_encode($controller->index());
});

$router->map('POST', '/api/news/store', function() use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->store();
});

$router->map('GET', '/api/news/create', function() use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->edit(null);
});

$router->map('GET|POST', '/api/news/edit/[i:id]', function($id) use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->edit($id);
});

$router->map('POST', '/api/news/updateSort', function() use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->updateSort();
});


$router->map('POST', '/api/news/updateStatus', function() use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->updateStatus();
});

$router->map('POST', '/api/news/delete/[i:id]', function($id) use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->destory($id);
});

$router->map('POST', '/api/news/batchDelete', function() use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->batchDestory();
});












$router->map('GET', '/api/news-categories', function() use ($pdo) {
    header('Content-Type: application/json');
    $controller = new NewsCategoryController($pdo);
    echo json_encode($controller->index());
});