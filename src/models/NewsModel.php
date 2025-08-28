<?php

class NewsModel
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
        $category_id,
        $title,
        $title_en,
        $description,
        $description_en,
        $keyword,
        $keyword_en,
        $content,
        $content_en,
        $key1,
        $key1_en,
        $key2,
        $key2_en,
        $key3,
        $key3_en,
        $key4,
        $key4_en,
        $key5,
        $key5_en,
        $url1,
        $url1_en,
        $url2,
        $url2_en,
        $url3,
        $url3_en,
        $url4,
        $url4_en,
        $url5,
        $url5_en
    )
    {

        $sql = "INSERT INTO news (
                  category_id, 
                  title, 
                  title_en, 
                  description, 
                  description_en, 
                  keyword, 
                  keyword_en, 
                  content, 
                  content_en, 
                  key1, 
                  key1_en, 
                  key2, 
                  key2_en, 
                  key3, 
                  key3_en, 
                  key4, 
                  key4_en, 
                  key5, 
                  key5_en, 
                  url1, 
                  url1_en, 
                  url2, 
                  url2_en, 
                  url3,
                  url3_en, 
                  url4, 
                  url4_en, 
                  url5, 
                  url5_en, 
                  created_at
                  )
        VALUES (
                :category_id, 
                :title, 
                :title_en, 
                :description, 
                :description_en,
                :keyword, 
                :keyword_en, 
                :content, 
                :content_en, 
                :key1, 
                :key1_en, 
                :key2, 
                :key2_en, 
                :key3, 
                :key3_en, 
                :key4, 
                :key4_en, 
                :key5, 
                :key5_en, 
                :url1, 
                :url1_en, 
                :url2, 
                :url2_en, 
                :url3, 
                :url3_en, 
                :url4, 
                :url4_en, 
                :url5, 
                :url5_en, 
                NOW()
                )";
        $stmt = $this->pdo->prepare($sql);


        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title);
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
        $stmt->bindParam(':title_en', $title_en);
        $stmt->bindParam(':description_en', $description_en);
        $stmt->bindParam(':keyword_en', $keyword_en);
        $stmt->bindParam(':content_en', $content_en);
        $stmt->bindParam(':key1_en', $key1_en);
        $stmt->bindParam(':url1_en', $url1_en);
        $stmt->bindParam(':key2_en', $key2_en);
        $stmt->bindParam(':url2_en', $url2_en);
        $stmt->bindParam(':key3_en', $key3_en);
        $stmt->bindParam(':url3_en', $url3_en);
        $stmt->bindParam(':key4_en', $key4_en);
        $stmt->bindParam(':url4_en', $url4_en);
        $stmt->bindParam(':key5_en', $key5_en);
        $stmt->bindParam(':url5_en', $url5_en);

        return $stmt->execute();
    }


    public function count($params) {
        $sql = "SELECT COUNT(*) FROM news n WHERE 1=1";

        $conditions = [];
        $bindValues = [];

        // 添加分类筛选
        if (!empty($params['category_id'])) {
            $conditions[] = "n.category_id = :category_id";
            $bindValues[':category_id'] = (int)$params['category_id'];
        }

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

    public function find($id) {
        $sql = "SELECT n.*, nc.title AS category_name
               FROM news n
               LEFT JOIN news_categories nc ON n.category_id = nc.id
               WHERE n.id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE news SET 
                category_id = :category_id, 
                title = :title, 
                description = :description, 
                keyword = :keyword, 
                content = :content, 
                key1 = :key1, 
                url1 = :url1, 
                key2 = :key2, 
                url2 = :url2, 
                key3 = :key3, 
                url3 = :url3, 
                key4 = :key4, 
                url4 = :url4, 
                key5 = :key5, 
                url5 = :url5, 
                title_en = :title_en, 
                description_en = :description_en, 
                keyword_en = :keyword_en, 
                content_en = :content_en, 
                key1_en = :key1_en, 
                url1_en = :url1_en, 
                key2_en = :key2_en, 
                url2_en = :url2_en, 
                key3_en = :key3_en, 
                url3_en = :url3_en, 
                key4_en = :key4_en, 
                url4_en = :url4_en, 
                key5_en = :key5_en, 
                url5_en = :url5_en 
                WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':category_id' => (int)$data['category_id'],
            ':title' => $data['title'],
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
            ':title_en' => $data['title_en'],
            ':description_en' => $data['description_en'],
            ':keyword_en' => $data['keyword_en'],
            ':content_en' => $data['content_en'],
            ':key1_en' => $data['key1_en'],
            ':url1_en' => $data['url1_en'],
            ':key2_en' => $data['key2_en'],
            ':url2_en' => $data['url2_en'],
            ':key3_en' => $data['key3_en'],
            ':url3_en' => $data['url3_en'],
            ':key4_en' => $data['key4_en'],
            ':url4_en' => $data['url4_en'],
            ':key5_en' => $data['key5_en'],
            ':url5_en' => $data['url5_en']
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

    public function search($params) {
        $sql = "SELECT n.*, nc.title AS category_name FROM news n
                LEFT JOIN news_categories nc ON n.category_id = nc.id
                WHERE 1=1";

        $conditions = [];
        $bindValues = [];

        // 分类筛选条件
        if (!empty($params['category_id'])) {
            $conditions[] = "n.category_id = :category_id";
            $bindValues[':category_id'] = (int)$params['category_id'];
        }

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
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM news WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

  public function deleteMultiple(array $ids) {
        // 创建ID占位符
      $placeholders = implode(',', array_fill(0,count($ids), '?'));

      $sql = "DELETE FROM news WHERE id IN ($placeholders)";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($ids);

      return $stmt->rowCount();
  }
}