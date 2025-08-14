<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once APP_PATH . '/models/NewsModel.php';

// 创建模型实例
$newsModel = new NewsModel($pdo);

// 获取分类ID（假设至少有一个分类）
$categories = $newsModel->getCategories();
if (empty($categories)) {
    die("请先创建新闻分类");
}

$categoryIds = array_column($categories, 'id');

// 假数据生成函数
function generateFakeNewsData($categoryIds) {
    $faker = Faker\Factory::create('en_US');

    $data = [
        'title' => $faker->sentence(6),
        'category_id' => $faker->randomElement($categoryIds),
        'description' => $faker->sentence(20),
        'keyword' => implode(',', $faker->words(5)),
        'content' => '<p>' . implode('</p><p>', $faker->paragraphs(5)) . '</p>'
    ];

    // 生成5组关键词和链接
    for ($i = 1; $i <= 5; $i++) {
        $data['key'.$i] = $faker->word;
        $data['url'.$i] = $faker->url;
    }

    return $data;
}

// 生成并插入100条数据
$successCount = 0;
for ($i = 0; $i <= 100; $i++) {
    $data = generateFakeNewsData($categoryIds);

    if ($newsModel->saveNews(
        $data['title'],
        $data['category_id'],
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
        $data['url5']
    )) {
        $successCount++;
    }
}

echo "成功插入{$successCount} 条假数据";