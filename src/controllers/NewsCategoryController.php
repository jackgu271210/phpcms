<?php
namespace App\controllers;

use NewsCategoryModel;

require_once __DIR__ . '/../models/NewsCategoryModel.php';

class NewsCategoryController {
    private $pdo;
    private $newsCategoryModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->newsCategoryModel = new NewsCategoryModel($pdo);
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
            'offset' => $offset,
            'limit' => $limit
        ];

        $data = $this->newsCategoryModel->search($params);
        $total = $this->newsCategoryModel->count($params);

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
            'title' => isset($_POST['title']) ? trim($_POST['title']) : '',
            'description' => isset($_POST['description']) ? trim($_POST['description']) : '',
            'keyword' => isset($_POST['keyword']) ? trim($_POST['keyword']) : '',
            'title_en' => isset($_POST['title_en']) ? trim($_POST['title_en']) : '',
            'description_en' => isset($_POST['description_en']) ? trim($_POST['description_en']) : '',
            'keyword_en' => isset($_POST['keyword_en']) ? trim($_POST['keyword_en']) : ''
        ];

        // 验证数据
        if (empty($data['title'])) {
            echo json_encode([
                'code' => 1,
                'msg' => '标题不能为空'
            ]);
            exit;
        }

        //调用模型保存数据
        try {
            if ($id) {
                $this->newsCategoryModel->update($id, $data);
                echo json_encode([
                    'code' => 0,
                    'msg' => '更新成功'
                ]);
            } else {
                $this->newsCategoryModel->create(
                    $data['title'],
                    $data['description'],
                    $data['keyword'],
                    $data['title_en'],
                    $data['description_en'],
                    $data['keyword_en']
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

    public function edit($id = null) {
       if ($_SERVER['REQUEST_METHOD'] === 'POST') {
           $this->store();
           exit;
       }

       $newsCategory = $id ? $this->newsCategoryModel->find($id) : [];
       if ($id && !$newsCategory) {
           http_response_code(404);
           require APP_PATH . '/views/404.php';
           exit;
       }

       require APP_PATH . '/views/news_category/edit.php';
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
            $affectedRows = $this->newsCategoryModel->delete($id);
            if ($affectedRows > 0) {
                echo json_encode([
                    'code' => 0,
                    'msg' => '删除成功'
                ]);
            } else {
                echo json_encode([
                    'code' => 1,
                    'msg' => '删除失败'
                ]);
            }
        } catch (PDOException $e) {
            echo json_encode([
                'code' => 1,
                'msg' => '删除失败：' . $e->getMessage()
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
            $affectedRows = $this->newsCategoryModel->deleteMultiple($ids);

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
            if ($this->newsCategoryModel->updateStatus($id, $status)) {
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
            if ($this->newsCategoryModel->updateSort($id, $sort)) {
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