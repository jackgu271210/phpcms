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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode([
                'code' => 1,
                'msg' => '无效的请求数据'
            ]);
            exit;
        }

        $id = isset($_POST['id']) && $_POST['id'] ? (int)$_POST['id'] : null;

        $data = [
            'title' => isset($_POST['title']) ? trim($_POST['title']) : '',
            'category_id' => isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0,
            'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
            'keyword' => isset($_POST['keyword']) ? trim($_POST['keyword']) : '',
            'content' => isset($_POST['content']) ? trim($_POST['content']) : '',
            'key1' => isset($_POST['key1']) ? trim($_POST['key1']) : '',
            'url1' => isset($_POST['url1']) ? trim($_POST['url1']) : '',
            'key2' => isset($_POST['key2']) ? trim($_POST['key2']) : '',
            'url2' => isset($_POST['url2']) ? trim($_POST['url2']) : '',
            'key3' => isset($_POST['key3']) ? trim($_POST['key3']) : '',
            'url3' => isset($_POST['url3']) ? trim($_POST['url3']) : '',
            'key4' => isset($_POST['key4']) ? trim($_POST['key4']) : '',
            'url4' => isset($_POST['url4']) ? trim($_POST['url4']) : '',
            'key5' => isset($_POST['key5']) ? trim($_POST['key5']) : '',
            'url5' => isset($_POST['url5']) ? trim($_POST['url5']) : ''
        ];

        //验证数据
        if (empty($data['title']) || $data['category_id'] === 0) {
            echo json_encode([
                'code' => 1,
                'msg' => '标题和分类不能为空'
            ]);
            exit;
        }

        //调用模型保存数据
        try {
            if ($id) {
                $this->newsModel->updateNews($id, $data);
                echo json_encode([
                    'code' => 0,
                    'msg' => '更新成功'
                ]);
            } else {
                $this->newsModel->saveNews(
                    $data['title'], $data['category_id'], $data['description'], $data['keyword'], $data['content'],
                    $data['key1'], $data['url1'], $data['key2'], $data['url2'], $data['key3'], $data['url3'],
                    $data['key4'], $data['url4'], $data['key5'], $data['url5']
                );
                echo json_encode([
                    'code' => 0,
                    'msg' => '添加成功'
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'code' => 1,
                'msg' => '操作失败: ' . $e->getMessage()
            ]);
            exit;
        }
    }

    public function categories() {
        $categories = $this->newsModel->getCategories();
        echo json_encode([
            'code' => 0,
            'msg' => '',
            'data' => $categories
        ]);
    }

    public function listNews() {
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $category_id = isset($_GET['category_id']) ? (int)$_GET['category_id'] : null;
            $offset = ($page - 1) * $limit;

            $total = $this->newsModel->getNewsCount($category_id); // 假设有 getNewsCount() 方法返回总数
            $data = $this->newsModel->getNewsList($offset, $limit, $category_id); //假设支持分页

            return [
                'code' => 0,
                'msg' => '',
                'count' => $total,
                'data' => $data
            ];
    }

    public function edit($id = null) {
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $this->save();
           exit;
       }

       $news = $id ? $this->newsModel->getNewsById($id) : [];
       if ($id && !$news) {
           http_response_code(404);
           require APP_PATH . '/views/404.php';
           exit;
       }

       $categories = $this->newsModel->getCategories();
       require APP_PATH . '/views/news/edit.php';
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'code' => 1,
                'msg' => '方法不允许'
            ]);
            exit;
        }
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


    public function batchDelete() {
        // 只接受POST请求
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'code' => 1,
                'msg' => '方法不允许'
            ]);
            exit;
        }

        $input = json_decode(file_get_contents('php//input'), true);
        $ids = $input['ids'] ?? [];

        if (empty($ids)) {
            echo json_encode([
               'code' => 1,
               'msg' => '请至少选择一条记录'
            ]);
            exit;
        }

        // 过滤非数字ID
        $ids = array_filter($ids, function($id) {
            return is_numeric($id);
        });

        if (empty($ids)) {
            echo json_encode([
                'code' => 1,
                'msg' => '无效ID'
            ]);
            exit;
        }

        try {
            // 调用模型进行批量删除
            $affectedRows = $this->newsModel->batchDelete($ids);

            if ($affectedRows > 0) {
                echo json_encode([
                   'code' => 0,
                   'msg' => '成功删除' . $affectedRows . '条记录'
                ]);
            } else {
                echo json_encode([
                   'code' => 1,
                   'msg' => '删除失败，记录可能不存在'
                ]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'code' => 1,
                'msg' => '删除失败' . $e->getMessage()
            ]);
        }
    }
}