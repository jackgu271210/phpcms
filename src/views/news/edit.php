<!--头部-->
<?php require_once APP_PATH . '/views/layouts/style.php' ?>
<!--头部-->

<?php
$data = $news ?? [];
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
                <label class="layui-form-label">新闻内容</label>
                <div class="layui-input-block">
                    <div id="editor-wrapper-cn">
                        <div id="toolbar-container-cn"><!-- 工具栏 --></div>
                        <div id="editor-container-cn"><!-- 编辑器 --></div>
                    </div>
                    <textarea name="content" id="editor-content-cn" style="display:none;"><?php
                        echo htmlspecialchars($data['content'] ?? '');
                        ?>
                    </textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">新闻内容（英）</label>
                <div class="layui-input-block">
                    <div id="editor-wrapper-en">
                        <div id="toolbar-container-en"><!-- 工具栏 --></div>
                        <div id="editor-container-en"><!-- 编辑器 --></div>
                    </div>
                    <textarea name="content_en" id="editor-content-en" style="display:none;"><?php
                        echo htmlspecialchars($data['content_en'] ?? '');
                        ?>
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
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <div class="layui-form-item">
                    <div class="layui-row layui-col-space16">
                        <div class="layui-col-xs6">
                            <label class="layui-form-label">内联关键词<?php echo $i; ?>（英）</label>
                            <div class="layui-input-block">
                                <input type="text" name="key<?php echo $i; ?>_en"
                                       value="<?php echo htmlspecialchars($data["key{$i}_en"] ?? ''); ?>"
                                       class="layui-input">
                            </div>
                        </div>
                        <div class="layui-col-xs6">
                            <label class="layui-form-label">链接<?php echo $i; ?>（英）</label>
                            <div class="layui-input-block">
                                <input type="text" name="url<?php echo $i; ?>_en"
                                       value="<?php echo htmlspecialchars($data["url{$i}_en"] ?? ''); ?>"
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

<!--底部-->
<?php require_once APP_PATH . '/views/layouts/script.php' ?>
<!--底部-->
<script>
    layui.use(['form', 'jquery'], function () {
        var form = layui.form;
        var $ = layui.$;
        var layer = layui.layer;

        // 配置多个编辑器
        $(document).ready(function() {
            initEditors([
                {
                    wrapperId: 'editor-wrapper-cn',
                    toolbarId: 'toolbar-container-cn',
                    editorId: 'editor-container-cn',
                    contentId: 'editor-content-cn',
                    customConfig: {}
                },
                {
                    wrapperId: 'editor-wrapper-en',
                    toolbarId: 'toolbar-container-en',
                    editorId: 'editor-container-en',
                    contentId: 'editor-content-en',
                    customConfig: {}
                }
            ], $)
        });

        // 表单提交
        form.on('submit(formSubmit)', function (data) {
            var loading = layer.load(2);

            $.ajax({
                url: '/api/news/store',
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
