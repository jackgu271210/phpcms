
<!--头部-->
<?php require_once '../src/views/layouts/header.php' ?>
<!--头部-->

<!--头部-->
<?php require_once '../src/views/layouts/sidebar.php' ?>
<!--头部-->

<!--内容主题-->
<div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="padding: 30px;">
        <div class="layui-card layui-panel">
            <div class="layui-card-header">
                <h2 class="">新闻列表</h2>
            </div>
            <table id="newsTable" lay-filter="newsTable"></table>
            <script type="text/html" id="toolDemo">
                <div class="layui-clear-space">
                    <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                    <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
                </div>
            </script>
        </div>
        <br><br>
    </div>
</div>
<!--内容主题-->

<!--底部-->
<?php require_once '../src/views/layouts/footer.php' ?>
<!--底部-->

<script src="../assets/layui/layui.js"></script>
<script src="../assets/wangeditor/js/index.js"></script>
<script>
    layui.use(['table'], function() {
        var table = layui.table;

        // 渲染表格
        table.render({
            elem: '#newsTable',
            url: '/news/list', // 如果使用 AJAX，可填入数据接口 URL
            method: 'GET',
            page: true, // 开启分页
            limit: 10, // 每页显示数量
            limits: [10, 20, 30], // 分页选项
            headers: {'Cache-Control': 'no-cache'},
            cols: [[
                {field: 'id', title: 'ID', width: 150, sort: true},
                {field: 'title', title: '标题'},
                {field: 'category_id', title: '类别'},
                {field: 'created_at', title: '加入时间', width: 200, sort: true},
                {fixed: 'right', title:'操作', width: 134, minWidth: 125, templet: '#toolDemo'}
            ]],
            totalRow: false, // 是否显示总计行
            error: function(res, msg) {
                console.error('Table request failed:', res, msg);
                layer.msg('表格数据加载失败: ' + msg, {icon: 2});
            },
            done: function(res, curr, count) {
                console.log('Table data loaded:', res, curr, count);
                if (res.code !== 0) {
                    layer.msg('数据加载失败: ' + res.msg, {icon: 2});
                }
            }
        });
        // 监听操作事件
        table.on('tool(newsTable)', function(obj){
            var data = obj.data;
            var id = obj.data.id;
            if (obj.event === 'edit') {
                //弹出编辑弹窗
                layer.open({
                    type: 1,
                    title: '编辑新闻',
                    area: ['80%', '80%'],
                    content: `
                                <div style="padding: 30px;">
                                    <div class="layui-card layui-panel">
                                        <div class="layui-card-body">
                                            <form class="layui-form" method="POST" action="/news/save" enctype="multipart/form-data">
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">标题</label>
                                                    <div class="layui-input-block">
                                                        <input type="text" name="title" value="${data.title}" class="layui-input">
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">分类</label>
                                                    <div class="layui-input-inline">
                                                        <select name="category_id">
                                                            <option value="${data.category_id}">请选择</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">网页描述</label>
                                                    <div class="layui-input-block">
                                                        <textarea name="description" value="${data.description}"  class="layui-textarea"></textarea>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">关键词</label>
                                                    <div class="layui-input-block">
                                                        <input type="text" name="keyword" value="${data.keyword}" class="layui-input">
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label">新闻内容</label>
                                                    <div class="layui-input-block">
                                                        <div id="editor—wrapper">
                                                            <div id="toolbar-container"><!-- 工具栏 --></div>
                                                            <div id="editor-container"><!-- 编辑器 --></div>
                                                        </div>
                                                        <textarea name="content" id="editor-content" style="display:none;"></textarea>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <div class="layui-row layui-col-space16">
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">内联关键词1</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="key1" value="${data.key1}" class="layui-input">
                                                            </div>
                                                        </div>
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label"> 链接1</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="url1" value="${data.url1}" class="layui-input">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <div class="layui-row layui-col-space16">
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">内联关键词2</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="key2" value="${data.key2}" class="layui-input">
                                                            </div>
                                                        </div>
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">链接2</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="url2" value="${data.url2}" class="layui-input">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <div class="layui-row layui-col-space16">
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">内联关键词3</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="key3" value="${data.key3}" class="layui-input">
                                                            </div>
                                                        </div>
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">链接3</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="url3" value="${data.url3}" class="layui-input">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <div class="layui-row layui-col-space16">
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">内联关键词4</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="key4" value="${data.key4}" class="layui-input">
                                                            </div>
                                                        </div>
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">链接4</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="url4" value="${data.url4}" class="layui-input">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <div class="layui-row layui-col-space16">
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">内联关键词5</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="key5" value="${data.key5}" class="layui-input">
                                                            </div>
                                                        </div>
                                                        <div class="layui-col-xs6">
                                                            <label class="layui-form-label">链接5</label>
                                                            <div class="layui-input-block">
                                                                <input type="text" name="url5" value="${data.url5}" class="layui-input">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <div class="layui-input-block">
                                                        <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                                                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <br><br>
                                </div>
                    `
                })
            } else if (obj.event === 'delete') {
                layer.confirm('确定删除吗？', function(index) {
                    fetch('news/delete', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({id: id})
                    })
                        .then(response => response.json())
                        .then(res => {
                            if (res.code === 0) {
                               layer.msg(res.msg, {icon: 1});
                               table.reload('newsTable');
                               layer.close(index);
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        })
                        .catch(err => {
                            layer.msg('请求失败: ' + err, {icon: 2});
                        });
                }, function(index) {
                    layer.close(index); //取消时关闭确认框
                })
            }
        })
    });
</script>


