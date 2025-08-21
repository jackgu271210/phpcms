<?php
// index.php - 基础版入口文件

require __DIR__ . '/../vendor/autoload.php';

// 1. 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. 定义常量
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/src');


// 4. 启动会话(如果需要)
session_start();

require_once APP_PATH . '/../config/database.php';

$pdo = getDbConnection();

// 5. 初始化AltoRouter
$router = new AltoRouter();
$router->setBasePath('');

// 6. 定义路由
use App\controllers\NewsController;

// 新闻路由
$router->map('GET', '/news', function() use ($pdo) {
   require APP_PATH . '/views/news/list.php';
});

$router->map('GET', '/news/categories', function() use ($pdo) {
   $controller = new NewsController($pdo);
   $controller->categories();
});

$router->map('GET', '/news/list', function() use ($pdo) {
    header('Content-Type: application/json');
    $controller = new NewsController($pdo);
    echo json_encode($controller->listNews());
});

$router->map('POST', '/news/save', function() use ($pdo) {
   $controller = new NewsController($pdo);
   $controller->save();
});

$router->map('GET', '/news/add', function() use ($pdo) {
   $controller = new NewsController($pdo);
   $controller->edit(null);
});

$router->map('GET|POST', '/news/edit/[i:id]', function($id) use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->edit($id);
});

$router->map('POST', '/news/updateSort', function() use ($pdo) {
   $controller = new NewsController($pdo);
   $controller->updateSort();
});

$router->map('POST', '/news/updateStatus', function() use ($pdo) {
   $controller = new NewsController($pdo);
   $controller->updateStatus();
});

$router->map('POST', '/news/delete/[i:id]', function($id) use ($pdo) {
   $controller = new NewsController($pdo);
   $controller->delete($id);
});

$router->map('POST','/news/batchDelete', function() use ($pdo) {
    $controller = new NewsController($pdo);
    $controller->batchDelete();
});


// 其他路由

$router->map('GET', '/faker', function() {
   require APP_PATH . '/lib/generate_fake_data.php';
});

$router->map('GET|POST', '/upload', function() {
    require APP_PATH . '/lib/upload.php';
});

// 首页路由
$router->map('GET', '/', function() {
   require APP_PATH . '/views/index.php';
});

// 路由匹配和处理
$match = $router->match();

if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    // 匹配没有的路由，显示404
    http_response_code(404);
    require APP_PATH . '/views/404.php';
}
