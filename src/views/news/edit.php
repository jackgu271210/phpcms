<?php
$categories = $this->newsModel->getCategories();
$data = $news ?? [];
?>

<div style="padding: 30px;">
    <div class="layui-card layui-panel">
        <div class="layui-card-body">
            <form class="layui-form" method="POST" action="/news/save" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id'] ?? ''); ?>">
                <div class="layui-form-item">
                    <label class="layui-form-label">标题</label>
                    <div class="layui-input-block">
                        <input type="text" name="title" value="<?php echo htmlspecialchars($data['title'] ?? ''); ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">分类</label>
                    <div class="layui-input-inline">
                        <select name="category_id">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['id']); ?>" <?php echo isset($data['category_id']) && $data['category_id'] == $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['title']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">网页描述</label>
                    <div class="layui-input-block">
                        <textarea name="description" class="layui-textarea">
                            <?php echo htmlspecialchars($data['description'] ?? ''); ?>
                        </textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">关键词</label>
                    <div class="layui-input-block">
                        <input type="text" name="keyword" value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">新闻内容</label>
                    <div class="layui-input-block">
                        <div id="editor—wrapper">
                            <div id="toolbar-container"><!-- 工具栏 --></div>
                            <div id="editor-container"><!-- 编辑器 --></div>
                        </div>
                        <textarea name="content" id="editor-content" style="display:none;">
                            <?php echo htmlspecialchars($data['content'] ?? ''); ?>
                        </textarea>
                    </div>
                </div>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="layui-form-item">
                        <div class="layui-row layui-col-space16">
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">内联关键词<?php echo $i; ?></label>
                                <div class="layui-input-block">
                                    <input type="text" name="key<?php echo $i; ?>" value="<?php echo htmlspecialchars($data["key$i"] ?? ''); ?>" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">链接<?php echo $i; ?></label>
                                <div class="layui-input-block">
                                    <input type="text" name="url<?php echo $i; ?>" value="<?php echo htmlspecialchars($data["url$i"] ?? ''); ?>" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
            </form>
        </div>
    </div>
    <br><br>
</div>
<script src="../assets/layui/layui.js"></script>
<script src="../assets/wangeditor/js/index.js"></script>
<script src="../assets/js/news-common.js"></script>