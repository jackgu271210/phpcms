
<!--头部-->
<?php require_once APP_PATH . '/views/layouts/header.php' ?>
<!--头部-->

<!--侧边栏-->
<?php require_once APP_PATH . '/views/layouts/sidebar.php' ?>
<!--侧边栏-->

<!--内容主题-->
<div class="layui-body">
    <!-- 内容主体区域 -->
    <div class="layui-margin-5">
        <div class="layui-card layui-panel">
            <div class="layui-card-header">
                <h2 class="">新闻列表</h2>
            </div>
            <div class="layui-card-body">
                <div class="layui-table-view layui-form layui-border-box">
                    <div class="layui-row layui-table-tool">
                        <div style="float:left">
                            <button class="layui-btn" id="btnAdd">
                                <i class="layui-icon layui-icon-add-1"></i>
                                新增
                            </button>
                            <button class="layui-btn layui-btn-danger" id="btnBatchDelete">
                                <i class="layui-icon layui-icon-delete"></i>
                                批量删除
                            </button>
                        </div>
                        <div style="float:right">
                            <div class="layui-inline">
                                <div class="layui-input-wrap">
                                    <select name="category_id" lay-filter="category-filter">
                                        <option value="所有新闻"></option>
                                        <!--动态分类数据将通过JS提供-->
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix">
                                        <i class="layui-icon layui-icon-date"></i>
                                    </div>
                                    <input type="text" name="start_date" id="start_date" lay-verify="date"
                                           placeholder="开始日" autocomplete="off" class="layui-input date">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-wrap">
                                    <div class="layui-input-prefix">
                                        <i class="layui-icon layui-icon-date"></i>
                                    </div>
                                    <input type="text" name="end_date" id="end_date" lay-verify="date"
                                           placeholder="结束日" autocomplete="off" class="layui-input date">
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline layui-input-wrap">
                                    <input type="text" name="keyword" placeholder="关键词搜索"
                                           autocomplete="off" lay-affix="clear" class="layui-input">
                                </div>
                                <div class="layui-input-inline" style="padding: 0!important;">
                                    <button type="button" class="layui-btn" lay-submit lay-filter="search">
                                        <i class="layui-icon layui-icon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table id="newsTable" lay-filter="newsTable"></table>
                <script type="text/html" id="toolDemo">
                    <div class="layui-clear-space">
                        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
                        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">删除</a>
                    </div>
                </script>
            </div>
        </div>
        <br><br>
    </div>
</div>
<!--内容主题-->

<!--底部-->
<?php require_once APP_PATH .  '/views/layouts/footer.php' ?>
<!--底部-->
<script>
    layui.use(['table', 'form', 'laydate', 'layer'], function() {
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var laydate = layui.laydate;
        var $ = layui.$;

        // 初始化表格
        window.tableIns = table.render({
            elem: '#newsTable',
            url: '/news/list', // 如果使用 AJAX，可填入数据接口 URL
            page: true, // 开启分页
            limit: 10, // 每页显示数量
            limits: [10, 20, 30], // 分页选项
            cols: [[
                {type: 'checkbox', fixed: 'left'},
                {field: 'id', title: 'ID', width: 150, sort: true},
                {field: 'title', title: '标题'},
                {field: 'category_name', title: '类别'},
                {field: 'created_at', title: '加入时间', width: 200, sort: true},
                {fixed: 'right', title:'操作', width: 134, minWidth: 125, templet: '#toolDemo'}
            ]]
        });

        // 日期
        laydate.render({
           elem: '.date'
        });

        // 加载分类并初始化下拉菜单
        function loadCategories() {
            $.ajax({
                url: '/news/categories',
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.code === 0) {
                        var html = '<option value="">所有新闻</option>';
                        $.each(res.data, function(index, item) {
                            html += '<option value="'+item.id+'">' +item.title+ '</option>'
                        });
                        $('select[name="category_id"]').html(html);
                        form.render('select');
                    }
                }
            });
        }

        // 页面加载完成后初始化分类下拉框
        loadCategories();

        // 分类筛选事件
        form.on('select(category-filter)', function(data) {
            var where = {};
            if (data.value) {
                where.category_id = data.value;
            }
            tableIns.reload({
                where: where,
                page: {curr: 1}
            });
        });

        // 搜索按钮提交事件
        form.on('submit(search)', function(data) {
            console.log(data);
            var where = {};

            // 日期范围
            if (data.field.start_date) {
                where.start_date = data.field.start_date;
            }
            if (data.field.end_date) {
                where.end_date = data.field.end_date;
            }

            // 关键词
            if (data.field.keyword) {
                where.keyword = data.field.keyword;
            }

            //重新加载表格
            tableIns.reload({
                where: where,
                page: {curr: 1}
            });

            return false;
        })

        // 添加按钮
        $('#btnAdd').click(function() {
            showForm();
        });

        // 工具条事件
        table.on('tool(newsTable)', function (obj) {
            var data = obj.data;
            if (obj.event === 'edit') {
                showForm(data);
            } else if (obj.event === 'delete') {
                deleteData(data.id);
            }
        });

        // 显示表单弹窗
        function showForm(data) {
            data = data || {};
            var id = data.id;
            var title = data.id ? '编辑新闻' : '添加新闻';
            layer.open({
                type:2,
                title:title,
                area: ['80%', '80%'],
                content: '/news/edit/' + id, // 编辑页面URL
                success: function(layero, index) {
                    // 弹窗加载成功后的回调
                    var iframe = layero.find('iframe')[0];
                    var iframeWin = iframe.contentWindow;

                    // 如果是编辑，可以传递数据到iframe
                    if (id && data) {
                        // 延迟确保iframe加载完成
                        setTimeout(function() {
                            iframeWin.setFormData(data);
                        }, 300);
                    }
                }
            });
        }

        // 单个删除数据
        function deleteData(id) {
            layer.confirm('确认删除吗', function(index) {
                $.ajax({
                    url: '/news/delete/' + id,
                    method: 'POST',
                    dataType: 'json',
                    success: function(res) {
                        if (res.code === 0) {
                            layer.msg(res.msg, {icon:1});
                            tableIns.reload();
                        } else {
                            layer.msg(res.msg, {icon:2});
                        }
                    },
                    error: function() {
                        layer.msg('请求失败', {icon:2});
                        layer.close(index);
                    }
                })
            })
        }


        // 批量删除点击按钮事件
        $('#btnBatchDelete').click(function() {
            var checkStatus = table.checkStatus('newsTable');
            var selectedIds = checkStatus.data ? checkStatus.data.map(item => item.id) : [];

            if (selectedIds.length === 0) {
                layer.msg('请至少选择一条记录', {icon:2});
                return;
            }

            layer.confirm('确定要删除选中的' +selectedIds.length + '条记录吗', function(index) {
                var loading = layer.load();
                // 发送批量删除请求
                $.ajax({
                    url: '/news/batchDelete',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ids: selectedIds}),
                    success: function(res) {
                        layer.close(loading);
                        if (res.code === 0) {
                            layer.msg(res.msg, {icon:1});
                            tableIns.reload();
                        } else {
                            layer.msg(res.msg, {icon:2});
                        }
                    },
                    error: function() {
                        layer.msg('请求失败', {icon:2})
                    }
                });
                layer.close(index);
            });
        })
    });
</script>






