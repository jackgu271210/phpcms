<?php
// save.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];

    // 防止 XSS 攻击，清理输入（可选）
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    // 连接数据库（使用 PDO 示例）
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=editor_db', 'root', '123456');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare('INSERT INTO posts (content) VALUES (:content)');
        $stmt->execute(['content' => $content]);

        //重定向到显示页面
        header('Location: display.php');
        exit;

    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>