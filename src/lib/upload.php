<?php
header('Content-Type: application/json');

//上传目录
$uploadDir = 'uploads/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxFileSize = 2 * 1024 * 1024; //2MB

//确保上传目录存在
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    //验证文件大小
    if ($file['size'] > $maxFileSize) {
        echo json_encode(['errno' => 1,'message' => '文件过大']);
        exit;
    }

    //验证文件类型
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedTypes)) {
        echo json_encode(['errno' => 1,'message' => '文件格式错误']);
        exit;
    }

    //生成唯一的文件名
    $fileName = uniqid() . '_' .basename($file['name']);
    $filePath = $uploadDir . $fileName;

    //移动文件
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        //返回WangEditor需要的JSON响应
        echo json_encode([
            'errno' => 0,
            'data' => [
                'url' => '/' . $filePath,
                'alt' => htmlspecialchars(basename($file['name'])),
                'href' => ''
            ]
        ]);
    } else {
        echo json_encode(['error' => '文件上传失败']);
    }
} else {
    echo json_encode(['error' => '没有上传文件']);
}
?>