<!--头部-->
<?php require_once APP_PATH . '/views/layouts/style.php' ?>
<!--头部-->

<?php
$categories = $this->newsModel->getCategories();
$data = $news ?? [];
?>

<div style="padding: 30px;">
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
                        <input type="text" name="keyword"
                               value="<?php echo htmlspecialchars($data['keyword'] ?? ''); ?>" class="layui-input">
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
                                    <input type="text" name="key<?php echo $i; ?>"
                                           value="<?php echo htmlspecialchars($data["key$i"] ?? ''); ?>"
                                           class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">链接<?php echo $i; ?></label>
                                <div class="layui-input-block">
                                    <input type="text" name="url<?php echo $i; ?>"
                                           value="<?php echo htmlspecialchars($data["url$i"] ?? ''); ?>"
                                           class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endfor; ?>
                <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button type="submit" class="layui-btn" lay-submit lay-filter="formSubmit">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br><br>
</div>

<!--底部-->
<?php require_once APP_PATH . '/views/layouts/script.php' ?>
<!--底部-->
<script>
    layui.use(['form', 'jquery'], function () {
        var form = layui.form;
        var $ = layui.$;
        var layer = layui.layer;

        // 初始化编辑器函数
        function initEditor(initialContent) {
            const {createEditor, createToolbar} = window.wangEditor;

            const editorConfig = {
                placeholder: '请输入新闻内容...',
                onChange(editor) {
                    $('#editor-content').val(editor.getHtml());
                },
                MENU_CONF: {
                    uploadImage: {
                        server: '/news/upload',
                        fieldName: 'file',
                        maxFileSize: 20 * 1024 * 1024,
                        allowedFileTypes: ['image/*'],
                        customInsert(res, insertFn) {
                            if (res.code !== 0) {
                                layer.msg(res.msg, {icon: 2});
                                return;
                            }
                            insertFn(res.data.url);
                        }
                    }
                }
            };

            if (window.editor) {
                window.editor.destroy();
            }

            window.editor = createEditor({
                selector: '#editor-container',
                html: initialContent || '',
                config: editorConfig,
                mode: 'default',
            });

            createToolbar({
                editor: window.editor,
                selector: '#toolbar-container',
                config: {},
                mode: 'default',
            });
        }

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
        initEditor($('#editor-content').val() || '');

        // 表单提交
        form.on('submit(formSubmit)', function (data) {
            var loading = layer.load(2);

            $.ajax({
                url: '/news/save',
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
