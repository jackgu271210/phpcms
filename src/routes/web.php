<?php
// 页面路由

// 首页路由
$router->map('GET', '/', function() {
    require APP_PATH . '/views/index.php';
});

// 新闻列表路由
$router->map('GET', '/news', function() use ($pdo) {
    require APP_PATH . '/views/news/list.php';
});