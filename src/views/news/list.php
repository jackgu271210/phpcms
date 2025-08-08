
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
            <!-- Add news button (optional) -->
            <button class="layui-btn" id="addNews">添加新闻</button>
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
<script src="../assets/js/news-common.js"></script>
<script>
    layui.use(['table', 'jquery'], function() {
        var table = layui.table;
        var $ = layui.jquery;
        // 渲染表格
        table.render({
            elem: '#newsTable',
            url: '/news/list', // 如果使用 AJAX，可填入数据接口 URL
            method: 'GET',
            page: true, // 开启分页
            limit: 5, // 每页显示数量
            limits: [10, 20, 30], // 分页选项
            headers: {'Cache-Control': 'no-cache'},
            cols: [[
                {field: 'id', title: 'ID', width: 150, sort: true},
                {field: 'title', title: '标题'},
                {field: 'category_name', title: '类别'},
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

        initializeNewsModule();

        $('#addNews').on('click', function() {
            fetch('/news/edit/0') // 0 for new news
                .then(response => response.text())
                .then(html => {
                    layui.layer.open({
                        type: 1,
                        title: '添加新闻',
                        area: ['80%', '80%'],
                        content: html,
                        success: function(layero, index) {
                            setTimeout(() => {
                                if (typeof window.initEditor === 'function') {
                                    window.initEditor();
                                }
                                layui.form.render();
                            }, 100);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching add form:', error);
                    layui.layer.msg('加载添加表单失败: ' + error, { icon: 2 });
                });
        });
    });
    });
</script>


