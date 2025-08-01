<?php
//数据库配置
$dsn = 'mysql:host=localhost;dbname=editor_db;charset=utf8';
$username = 'root';
$password = '123456';
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //获取最新文章
    $stmt = $pdo->query('SELECT content FROM posts ORDER BY id DESC');
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error:' .$e->getMessage();
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>查看文章</title>
</head>
<body>
    <h1>最新文章</h1>
    <div>
        <?php
        if ($posts) {
            foreach ($posts as $post) {
                echo '<div class="post">';
                echo htmlspecialchars_decode($post['content']);
                echo '</div>';
            }
        } else {
            echo '<p>还没有任何内容！</p>';
        }
        ?>
    </div>
    <a href="index.php">新建文章</a>
</body>
</html>
