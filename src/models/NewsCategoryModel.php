<?php

class NewsCategoryModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * 添加新闻
     */
    public function create(
        $title,
        $description,
        $keyword,
        $title_en,
        $description_en,
        $keyword_en
    )
    {

        $sql = "INSERT INTO news_categories (
                             title, 
                             description, 
                             keyword, 
                             title_en, 
                             description_en, 
                             keyword_en, 
                             created_at
                             )
        VALUES (
                :title, 
                :description, 
                :keyword, 
                :title_en, 
                :description_en, 
                :keyword_en
                NOW()
                )";
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':keyword', $keyword);
        $stmt->bindParam(':title_en', $title_en);
        $stmt->bindParam(':description_en', $description_en);
        $stmt->bindParam(':keyword_en', $keyword_en);

        return $stmt->execute();
    }


    public function count($params) {
        $sql = "SELECT COUNT(*) FROM news_categories WHERE 1=1";

        $conditions = [];
        $bindValues = [];

        // 时间范围筛选
        if (!empty($params['start_date'])) {
            $conditions[] = "created_at >= :start_date";
            $bindValues[':start_date'] = $params['start_date'];
        }

        if (!empty($params['end_date'])) {
            $conditions[] = "created_at <= :end_date";
            $bindValues[':end_date'] = $params['end_date'];
        }

        // 关键词搜索
        if (!empty($params['keyword'])) {
            $conditions[] = "(title LIKE :keyword OR description LIKE :keyword)";
            $bindValues[':keyword'] = '%' . $params['keyword'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($bindValues as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchColumn();

    }

    public function find($id) {
        $sql = "SELECT * FROM news_categories WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE news_categories SET 
                           title = :title, 
                           description = :description, 
                           keyword = :keyword, 
                           title_en = :title_en, 
                           description_en = :description_en, 
                           keyword_en = :keyword_en
                           WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':keyword' => $data['keyword'],
            ':title_en' => $data['title_en'],
            ':description_en' => $data['description_en'],
            ':keyword_en' => $data['keyword_en']
        ]);
        return $stmt->rowCount();
    }

    public function updateStatus($id, $status) {
        $sql = "UPDATE news_categories SET status = :status WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':status' => $status]);
    }

    public function updateSort($id, $sort) {
        $sql = "UPDATE news_categories SET sort = :sort WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id, ':sort' => $sort]);
    }

    public function getCategories() {
        $sql = "SELECT id, title FROM news_categories ORDER BY COALESCE(sort, 9999), title";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($params) {
        $sql = "SELECT * FROM news_categories WHERE 1=1";

        $conditions = [];
        $bindValues = [];

        // 时间筛选范围
        if (!empty($params['start_date'])) {
            $conditions[] = "created_at >= :start_date";
            $bindValues[':start_date'] = $params['start_date'];
        }
        if (!empty($params['end_date'])) {
            $conditions[] = "created_at <= :end_date";
            $bindValues[':end_date'] = $params['end_date'];
        }

        // 关键词搜索
        if (!empty($params['keyword'])) {
            $conditions[] = "(title LIKE :keyword OR description LIKE :keyword)";
            $bindValues[':keyword'] = '%' . $params['keyword'] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " AND " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY CASE WHEN sort IS NULL THEN 1 ELSE 0 END, sort, created_at DESC";

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
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM news_categories WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

  public function deleteMultiple(array $ids) {
        // 创建ID占位符
      $placeholders = implode(',', array_fill(0,count($ids), '?'));

      $sql = "DELETE FROM news_categories WHERE id IN ($placeholders)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($ids);

      return $stmt->rowCount();
  }
}