<?php
// API路由
use App\controllers\NewsController;
use App\controllers\NewsCategoryController;
use App\controllers\ProductCategoryController;

// API路由-新闻

$router->map('GET', '/api/news/list', function() use ($pdo) {
   header('Content-Type: application/json');
   $controller = new NewsController($pdo);
   echo json_encode($controller->index());
});

$router->map('GET', '/api/news/categories', function() use ($pdo) {
    header('Content-Type: application/json');
    $controller = new NewsController($pdo);
    echo json_encode($controller->categories());
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


// API路由-新闻类别

$router->map('GET', '/api/news-category/list', function() use ($pdo) {
    header('Content-Type: application/json');
    $controller = new NewsCategoryController($pdo);
    echo json_encode($controller->index());
});

$router->map('POST', '/api/news-category/store', function() use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->store();
});

$router->map('GET', '/api/news-category/create', function() use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->edit(null);
});

$router->map('GET|POST', '/api/news-category/edit/[i:id]', function($id) use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->edit($id);
});

$router->map('POST', '/api/news-category/updateSort', function() use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->updateSort();
});


$router->map('POST', '/api/news-category/updateStatus', function() use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->updateStatus();
});

$router->map('POST', '/api/news-category/delete/[i:id]', function($id) use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->destory($id);
});

$router->map('POST', '/api/news-category/batchDelete', function() use ($pdo) {
    $controller = new NewsCategoryController($pdo);
    $controller->batchDestory();
});

// API路由-产品类别

$router->map('GET', '/api/product-category/list', function() use ($pdo) {
    header('Content-Type: application/json');
    $controller = new ProductCategoryController($pdo);
    echo json_encode($controller->index());
});

$router->map('POST', '/api/product-category/store', function() use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->store();
});

$router->map('GET', '/api/product-category/create', function() use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->edit(null);
});

$router->map('GET|POST', '/api/product-category/edit/[i:id]', function($id) use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->edit($id);
});

$router->map('POST', '/api/product-category/updateSort', function() use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->updateSort();
});


$router->map('POST', '/api/product-category/updateStatus', function() use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->updateStatus();
});

$router->map('POST', '/api/product-category/delete/[i:id]', function($id) use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->destory($id);
});

$router->map('POST', '/api/product-category/batchDelete', function() use ($pdo) {
    $controller = new ProductCategoryController($pdo);
    $controller->batchDestory();
});


