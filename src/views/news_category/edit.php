<!--头部-->
<?php require_once APP_PATH . '/views/layouts/style.php' ?>
<!--头部-->

<?php
    $data = $newsCategory ?? [];
?>
<div class="layui-card layui-panel">
    <div class="layui-card-body">
        <form class="layui-form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($data['id'] ?? ''); ?>">
            <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-block">
                    <input type="text" name="title"
                           value="<?php echo htmlspecialchars($data['title'] ?? ''); ?>"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标题（英）</label>
                <div class="layui-input-block">
                    <input type="text" name="title_en"
                           value="<?php echo htmlspecialchars($data['title_en'] ?? ''); ?>"
                           class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">网页描述</label>
                <div class="layui-input-block">
                    <textarea name="description" class="layui-textarea"><?php
                        echo htmlspecialchars($data['description'] ?? '');
                        ?>
                    </textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">网页描述（英）</label>
                <div class="layui-input-block">
                    <textarea name="description_en" class="layui-textarea"><?php
                        echo htmlspecialchars($data['description_en'] ?? '');
                        ?>
                    </textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">关键词</label>
                <div class="layui-input-block">
                    <input type="text" name="keyword"
                           value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">关键词（英）</label>
                <div class="layui-input-block">
                    <input type="text" name="keyword_en"
                           value="<?php echo htmlspecialchars($data['keyword_en'] ?? ''); ?>" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button type="submit" class="layui-btn" lay-submit lay-filter="formSubmit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!--底部-->
<?php require_once APP_PATH . '/views/layouts/script.php' ?>
<!--底部-->
<script>
    layui.use(['form', 'jquery'], function () {
        var form = layui.form;
        var $ = layui.$;
        var layer = layui.layer;

        // 设置表单数据函数
        window.setFormData = function (data) {
            // 基础字段
            $('input[name="title"]').val(data.title || '');
            $('select[name="category_id"]').val(data.category_id || '');
            $('textarea[name="description"]').val(data.description || '');
            $('input[name="keyword"]').val(data.keyword || '');

            // 编辑器内容
            $('#editor-content').val(data.content || '');
            initEditor(data.content || '');

            // 动态字段 key1-url5
            for (var i = 1; i <= 5; i++) {
                $('input[name="key' + i + '"]').val(data['key' + i] || '');
                $('input[name="url' + i + '"]').val(data['url' + i] || '');
            }

            form.render();
        };

        // 初始化
        form.render();

        // 表单提交
        form.on('submit(formSubmit)', function (data) {
            var loading = layer.load(2);

            $.ajax({
                url: '/api/news-category/store',
                type: 'POST',
                data: data.field,
                dataType: 'json',
                success: function (res) {
                    layer.close(loading);

                    // 检查响应是否有效
                    if (!res || typeof res.code === 'undefined') {
                        layer.msg('无效的响应格式', {icon: 2});
                        return;
                    }

                    if (res.code === 0) {
                        // 成功处理
                        if (parent && parent.layer && parent.tableIns) {
                            // 在父窗口中显示消息
                            parent.layer.msg(res.msg, {icon: 1}, function () {
                                parent.layer.closeAll();
                                parent.tableIns.reload();
                            });
                        } else {
                            layer.msg(res.msg, {icon: 1}, function() {
                                location.reload();
                            });
                        }
                    } else if (res.code === 1) {
                        // 错误处理 - 在当前iframe显示错误
                        // 在父窗口中显示消息
                        parent.layer.msg(res.msg, {icon: 2}, function () {
                            parent.layer.closeAll();
                        });
                    }
                },
                error: function () {
                    layer.close(loading);
                    layer.msg('请求失败', {icon: 2});
                }
            });
            return false;
        });
    });
</script>
