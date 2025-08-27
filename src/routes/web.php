<?php
// 页面路由

// 首页路由
$router->map('GET', '/', function() {
    require APP_PATH . '/views/index.php';
});

// 产品列表路由
$router->map('GET', '/product-category', function() use ($pdo) {
    require APP_PATH . '/views/product_category/list.php';
});

// 新闻列表路由
$router->map('GET', '/news', function() use ($pdo) {
    require APP_PATH . '/views/news/list.php';
});

// 新闻分类列表路由
$router->map('GET', '/news-category', function() use ($pdo) {
    require APP_PATH . '/views/news_category/list.php';
});