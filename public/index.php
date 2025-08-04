<?php
// index.php - 基础版入口文件

// 1. 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. 定义常量
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/app');
define('LIB_PATH', ROOT_PATH . '/lib');

// 3. 自动加载
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// 4. 启动会话(如果需要)
session_start();

// 5. 路由处理
$request = $_SERVER['REQUEST_URI'];
$request = str_replace('/your_project', '', $request); // 如果项目在子目录

switch ($request) {
    case '/':
        require APP_PATH . '/views/index.php';
        break;
    case '/display':
        require APP_PATH . '/views/display.php';
        break;
    case '/lib/save':
        require LIB_PATH . '/save.php';
        break;
    default:
        http_response_code(404);
        require APP_PATH . '/views/404.php';
        break;
}