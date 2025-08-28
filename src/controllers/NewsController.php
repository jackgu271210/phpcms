<?php
namespace App\controllers;

use NewsModel;

require_once __DIR__ . '/../models/NewsModel.php';

class NewsController {
    private $pdo;
    private $newsModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->newsModel = new NewsModel($pdo);
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        // 获取搜索参数
        $params = [
            'start_date' => isset($_GET['start_date']) ? $_GET['start_date'] : null,
            'end_date' => isset($_GET['end_date']) ? $_GET['end_date'] : null,
            'keyword' => isset($_GET['keyword']) ? trim($_GET['keyword']) : null,
            'category_id' => isset($_GET['category_id']) ? (int)$_GET['category_id'] : null,
            'offset' => $offset,
            'limit' => $limit
        ];

        $total = $this->newsModel->count($params);
        $data = $this->newsModel->search($params);

        return [
            'code' => 0,
            'msg' => '',
            'count' => $total,
            'data' => $data
        ];
    }

    public function store() {
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
            'category_id' => isset($_POST['category_id']) ? (int)$_POST['category_id'] : 0,
            'title' => isset($_POST['title']) ? trim($_POST['title']) : '',
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
            'url5' => isset($_POST['url5']) ? trim($_POST['url5']) : '',
            'title_en' => isset($_POST['title_en']) ? trim($_POST['title_en']) : '',
            'description_en' => isset($_POST['description_en']) ? trim($_POST['description_en']) : '',
            'keyword_en' => isset($_POST['keyword_en']) ? trim($_POST['keyword_en']) : '',
            'content_en' => isset($_POST['content_en']) ? trim($_POST['content_en']) : '',
            'key1_en' => isset($_POST['key1_en']) ? trim($_POST['key1_en']) : '',
            'url1_en' => isset($_POST['url1_en']) ? trim($_POST['url1_en']) : '',
            'key2_en' => isset($_POST['key2_en']) ? trim($_POST['key2_en']) : '',
            'url2_en' => isset($_POST['url2_en']) ? trim($_POST['url2_en']) : '',
            'key3_en' => isset($_POST['key3_en']) ? trim($_POST['key3_en']) : '',
            'url3_en' => isset($_POST['url3_en']) ? trim($_POST['url3_en']) : '',
            'key4_en' => isset($_POST['key4_en']) ? trim($_POST['key4_en']) : '',
            'url4_en' => isset($_POST['url4_en']) ? trim($_POST['url4_en']) : '',
            'key5_en' => isset($_POST['key5_en']) ? trim($_POST['key5_en']) : '',
            'url5_en' => isset($_POST['url5_en']) ? trim($_POST['url5_en']) : ''
        ];

        // 验证数据
        if (empty($data['title'])) {
            echo json_encode([
                'code' => 1,
                'msg' => '标题不能为空'
            ]);
            exit;
        }
        if (empty($data['content'])) {
            echo json_encode([
                'code' => 1,
                'msg' => '内容不能为空'
            ]);
            exit;
        }

        //调用模型保存数据
        try {
            if ($id) {
                $this->newsModel->update($id, $data);
                echo json_encode([
                    'code' => 0,
                    'msg' => '更新成功'
                ]);
            } else {
                $this->newsModel->create(
                    $data['category_id'],
                    $data['title'],
                    $data['description'],
                    $data['keyword'],
                    $data['content'],
                    $data['key1'],
                    $data['url1'],
                    $data['key2'],
                    $data['url2'],
                    $data['key3'],
                    $data['url3'],
                    $data['key4'],
                    $data['url4'],
                    $data['key5'],
                    $data['url5'],
                    $data['title_en'],
                    $data['description_en'],
                    $data['keyword_en'],
                    $data['content_en'],
                    $data['key1_en'],
                    $data['url1_en'],
                    $data['key2_en'],
                    $data['url2_en'],
                    $data['key3_en'],
                    $data['url3_en'],
                    $data['key4_en'],
                    $data['url4_en'],
                    $data['key5_en'],
                    $data['url5_en']
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
        return [
            'code' => 0,
            'msg' => '',
            'data' => $categories
        ];
    }

    public function edit($id = null) {
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $this->store();
           exit;
       }

       $news = $id ? $this->newsModel->find($id) : [];
       if ($id && !$news) {
           http_response_code(404);
           require APP_PATH . '/views/404.php';
           exit;
       }

       $categories = $this->newsModel->getCategories();
       require APP_PATH . '/views/news/edit.php';
    }

    public function destory($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'code' => 1,
                'msg' => '方法不允许'
            ]);
            exit;
        }
        try {
            $affectedRows = $this->newsModel->delete($id);
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


    public function batchDestory() {
        header('Content-Type: application/json');

        // 只接受POST请求
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'code' => 1,
                'msg' => '方法不允许'
            ]);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
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
            $affectedRows = $this->newsModel->deleteMultiple($ids);

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


    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'code' => 1,
                'msg' => '方法不允许'
            ]);
            exit;
        }
        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $status = isset($_POST['status']) ? (int)$_POST['status'] : 0;

        if ($id === null) {
            http_response_code(400);
            echo json_encode([
                'code' => 1,
                'msg' => '无效的ID'
            ]);
            exit;
        }

        try {
            if ($this->newsModel->updateStatus($id, $status)) {
                echo json_encode([
                    'code' => 0,
                    'msg' => '状态更新成功'
                ]);
            } else {
                echo json_encode([
                    'code' => 1,
                    'msg' => '状态更新失败'
                ]);
            }
        } catch(PDOException $e) {
            echo json_encode ([
                'code' => 1,
                'msg' => '状态更新失败: ' . $e->getMessage()
            ]);
        }
    }

    public function updateSort() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'code' => 1,
                'msg' => '方法不允许'
            ]);
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
        $sort = isset($_POST['sort']) ? (int)$_POST['sort'] : 0;

        if ($id === null) {
            http_response_code(400);
            echo json_encode([
                'code' => 1,
                'msg' => '无效的ID'
            ]);
            exit;
        }

        try {
            if ($this->newsModel->updateSort($id, $sort)) {
                echo json_encode([
                    'code' => 0,
                    'msg' => '排序更新成功'
                ]);
            } else {
                echo json_encode([
                    'code' => 1,
                    'msg' => '排序更新失败'
                ]);
            }
        } catch(PDOException $e) {
            echo json_encode([
                'code' => 1,
                'msg' => '排序更新失败: ' . $e->getMessage()
            ]);
        }
    }

}