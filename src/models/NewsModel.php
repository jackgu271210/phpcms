<?php

class NewsModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param $title
     * @param $category_id
     * @param $description
     * @param $keyword
     * @param $content
     * @param $key1
     * @param $url1
     * @param $key2
     * @param $url2
     * @param $key3
     * @param $url3
     * @param $key4
     * @param $url4
     * @param $key5
     * @param $url5
     * @return mixed
     * 添加新闻
     */
    public function saveNews($title, $category_id, $description, $keyword, $content, $key1, $url1, $key2, $url2, $key3, $url3, $key4, $url4, $key5, $url5)
    {

        $sql = "INSERT INTO news (title, category_id, description, keyword, content, key1, url1, key2, url2, key3, url3, key4, url4, key5, url5, created_at)
        VALUES (:title, :category_id, :description, :keyword, :content, :key1, :url1, :key2, :url2, :key3, :url3, :key4, :url4, :key5, :url5, NOW())";
        $stmt = $this->pdo->prepare($sql);

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

        return $stmt->execute();
    }

    /**
     * @return mixed
     * 查看新闻列表
     */
    public function getNewsList($offset, $limit, $category_id = null) {
        $sql = "SELECT n.*, nc.title AS category_name
                FROM news n
                LEFT JOIN news_categories nc ON n.category_id = nc.id";

        if ($category_id !== null) {
            $sql .= " WHERE n.category_id = :category_id";
        }

        $sql .= " ORDER BY n.created_at DESC LIMIT :offset, :limit";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        if ($category_id !== null) {
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewsCount($category_id = null) {
        $sql = "SELECT COUNT(*) FROM news";
        if ($category_id !== null) {
            $sql .= " WHERE category_id = :category_id";
        }
        $stmt = $this->pdo->prepare($sql);
        if ($category_id !== null) {
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getSearchCount($params) {
        $sql = "SELECT COUNT(*) FROM news n WHERE 1=1";

        $conditions = [];
        $bindValues = [];

        // 时间范围筛选
        if (!empty($params['start_date'])) {
            $conditions[] = "n.created_at >= :start_date";
            $bindValues[':start_date'] = $params['start_date'];
        }

        if (!empty($params['end_date'])) {
            $conditions[] = "n.created_at <= :end_date";
            $bindValues[':end_date'] = $params['end_date'];
        }

        // 关键词搜索
        if (!empty($params['keyword'])) {
            $conditions[] = "(n.title LIKE :keyword OR n.content LIKE :keyword)";
            $bindValues[':keyword'] = '%' . $params['keyword'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($bindValues as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();
        return $stmt->fetchColumn();

    }

    public function getNewsById($id) {
        $sql = "SELECT n.*, nc.title AS category_name
               FROM news n
               LEFT JOIN news_categories nc ON n.category_id = nc.id
               WHERE n.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateNews($id, $data) {
        $sql = "UPDATE news SET title = :title, category_id = :category_id, description = :description, keyword = :keyword, 
                content = :content, key1 = :key1, url1 = :url1, key2 = :key2, url2 = :url2, key3 = :key3, url3 = :url3, 
                key4 = :key4, url4 = :url4, key5 = :key5, url5 = :url5 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':category_id' => (int)$data['category_id'],
            ':description' => $data['description'],
            ':keyword' => $data['keyword'],
            ':content' => $data['content'],
            ':key1' => $data['key1'],
            ':url1' => $data['url1'],
            ':key2' => $data['key2'],
            ':url2' => $data['url2'],
            ':key3' => $data['key3'],
            ':url3' => $data['url3'],
            ':key4' => $data['key4'],
            ':url4' => $data['url4'],
            ':key5' => $data['key5'],
            ':url5' => $data['url5'],
        ]);
        return $stmt->rowCount();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE news SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }

    public function updateSort($id, $sort) {
        $sql = "UPDATE news SET sort = :sort WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':sort' => $sort]);
    }

    public function getCategories() {
        $stmt = $this->pdo->query("SELECT id, title FROM news_categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchNews($params) {
        $sql = "SELECT n.*, nc.title AS category_name FROM news n
                LEFT JOIN news_categories nc ON n.category_id = nc.id
                WHERE 1=1";

        $conditions = [];
        $bindValues = [];

        // 时间筛选范围
        if (!empty($params['start_date'])) {
            $conditions[] = "n.created_at >= :start_date";
            $bindValues[':start_date'] = $params['start_date'];
        }
        if (!empty($params['end_date'])) {
            $conditions[] = "n.created_at <= :end_date";
            $bindValues[':end_date'] = $params['end_date'];
        }

        // 关键词搜索
        if (!empty($params['keyword'])) {
            $conditions[] = "(n.title LIKE :keyword OR n.content LIKE :keyword)";
            $bindValues[':keyword'] = '%' . $params['keyword'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY CASE WHEN n.sort IS NULL THEN 1 ELSE 0 END, n.sort, n.created_at DESC";

        if (isset($params['offset']) && isset($params['limit'])) {
            $sql .= " LIMIT :offset, :limit";
            $bindValues[':offset'] = (int)$params['offset'];
            $bindValues[':limit'] = (int)$params['limit'];
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($bindValues as $key => $value) {
            $paramType = is_int($value) ? PDO::PARAM_INT :PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $paramType);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $id
     * @return mixed
     * 删除新闻
     */
    public function deleteNews($id) {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

  public function batchDelete(array $ids) {
        // 创建ID占位符
      $placeholders = implode(',', array_fill(0,count($ids), '?'));

      $sql = "DELETE FROM news WHERE id IN ($placeholders)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($ids);

      return $stmt->rowCount();
  }
}