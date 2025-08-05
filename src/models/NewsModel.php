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
    public function getNewsList($offset, $limit) {
        $stmt = $this->pdo->prepare("SELECT * FROM news LIMIT :offset, :limit");
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewsCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM news");
        return $stmt->fetchColumn();
    }
}