<?php
// index.php - 基础版入口文件



// 1. 设置错误报告
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 2. 定义常量
define('ROOT_PATH', __DIR__ . '/..');
define('APP_PATH', ROOT_PATH . '/src');

// 3. 自动加载
spl_autoload_register(function ($class) {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// 4. 启动会话(如果需要)
session_start();

require_once APP_PATH . '/../config/database.php';

$pdo = getDbConnection();

// 5. 路由处理
$request = $_SERVER['REQUEST_URI'];
$request = trim($request, '/'); //去除首尾斜杠
$parts = explode('/', $request);
$action = isset($parts[1]) ? $parts[1] : '';

switch ($parts[0]) {
    case 'news':
        require APP_PATH . '/controllers/NewsController.php';
        $controller = new NewsController($pdo);
        $id = isset($parts[2]) ? (int)$parts[2] : null;

        switch ($action) {
            case '':
                $controller->listNews();
                break;
            case 'save':
                $controller->save();
                break;
            case 'edit':
                $controller->edit($id);
                break;
            case 'delete':
                $controller->delete($id);
                break;
            default:
                http_response_code(404);
                require APP_PATH . '/views/404.php';
                break;
        }
        break;
    case '':
        require APP_PATH . '/views/index.php';
        break;
    default:
        http_response_code(404);
        require APP_PATH . '/views/404.php';
        break;
}