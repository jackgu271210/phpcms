<?php
// lib/save.php

// 包含配置文件
require_once __DIR__ . '/../config.php';

// 获取数据库连接
$pdo = getDBConnection();

// 检查是否为 POST 请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 获取表单数据
    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $keyword = isset($_POST['keyword']) ? trim($_POST['keyword']) : '';
    $content = isset($_POST['content']) ? trim($_POST['content']) : '';
    $key1 = isset($_POST['key1']) ? trim($_POST['key1']) : '';
    $url1 = isset($_POST['url1']) ? trim($_POST['url1']) : '';
    $key2 = isset($_POST['key2']) ? trim($_POST['key2']) : '';
    $url2 = isset($_POST['url2']) ? trim($_POST['url2']) : '';
    $key3 = isset($_POST['key3']) ? trim($_POST['key3']) : '';
    $url3 = isset($_POST['url3']) ? trim($_POST['url3']) : '';
    $key4 = isset($_POST['key4']) ? trim($_POST['key4']) : '';
    $url4 = isset($_POST['url4']) ? trim($_POST['url4']) : '';
    $key5 = isset($_POST['key5']) ? trim($_POST['key5']) : '';
    $url5 = isset($_POST['url5']) ? trim($_POST['url5']) : '';

    // 验证数据
    if (empty($title) || $category_id === 0) {
        die("标题和分类不能为空！");
    }

    // 准备 SQL 语句
    $sql = "INSERT INTO news (title, category_id, description, keyword, content, key1, url1, key2, url2, key3, url3, key4, url4, key5, url5, created_at) 
            VALUES (:title, :category_id, :description, :keyword, :content, :key1, :url1, :key2, :url2, :key3, :url3, :key4, :url4, :key5, :url5, NOW())";
    $stmt = $pdo->prepare($sql);

    // 绑定参数
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':keyword', $keyword);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':key1', $key1);
    $stmt->bindParam(':url1', $url1);
    $stmt->bindParam(':key2', $key2);
    $stmt->bindParam(':url2', $url2);
    $stmt->bindParam(':key3', $key3);
    $stmt->bindParam(':url3', $url3);
    $stmt->bindParam(':key4', $key4);
    $stmt->bindParam(':url4', $url4);
    $stmt->bindParam(':key5', $key5);
    $stmt->bindParam(':url5', $url5);

    // 执行插入
    try {
        $stmt->execute();

        header('Location: /display');
        exit;
    } catch (PDOException $e) {
        echo "添加失败: " . $e->getMessage();
    }
} else {
    http_response_code(403);
    echo "禁止直接访问！";
}