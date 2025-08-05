
<?php
//config.php

//数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'phpcms');
define('DB_USER', 'root');
define('DB_PASS', '123456');

//数据库连接函数
function getDbConnection() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
                DB_USER,
                DB_PASS
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("数据库连接失败: " . $e->getMessage());
        }
    }
    return $pdo;
}
?>