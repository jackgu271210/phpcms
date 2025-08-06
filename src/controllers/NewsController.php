<?php
require_once __DIR__ . '/../models/NewsModel.php';

class NewsController {
    private $pdo;
    private $newsModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->newsModel = new NewsModel($pdo);
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //获取表单数据
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

            //验证数据
            if (empty($title) || $category_id === 0) {
                http_response_code(400);
                echo '标题和分类不能为空';
                return;
            }

            //调用模型保存数据
            try {
                $this->newsModel->save($title, $category_id, $description, $keyword, $content, $key1, $url1, $key2, $url2, $key3, $url3, $key4, $url4, $key5, $url5);
                header('Location: /display');
            } catch (PDOException $e) {
                http_response_code(500);
                echo '添加失败: ' . $e->getMessage();
            }
        } else {
            http_response_code(403);
            echo '禁止直接访问';
        }
    }

    public function listNews() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;

            $total = $this->newsModel->getNewsCount(); // 假设有 getNewsCount() 方法返回总数
            $data = $this->newsModel->getNewsList($offset, $limit); //假设支持分页

            return [
                'code' => 0,
                'msg' => '',
                'count' => $total,
                'data' => $data
            ];
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $affectedRows = $this->newsModel->deleteNews($id);
                if ($affectedRows > 0) {
                    echo json_encode([
                        'code' => 0,
                        'msg' => '新闻删除成功'
                    ]);
                } else {
                    echo json_encode([
                        'code' => 1,
                        'msg' => '新闻删除失败'
                    ]);
                }
            } catch (PDOException $e) {
                echo json_encode([
                    'code' => 1,
                    'msg' => '新闻删除失败：' . $e->getMessage()
                ]);
            }
        }
    }
}