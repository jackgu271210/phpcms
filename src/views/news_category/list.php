<!--style-->
<?php require_once APP_PATH . '/views/layouts/style.php' ?>
<!--style-->


<!--内容主题-->
<div class="layui-card layui-panel">
    <div class="layui-card-body">
        <div class="layui-table-view layui-block layui-form layui-border-box">
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
        <script type="text/html" id="statusTemplate">
            <input type="checkbox" lay-skin="switch" lay-text="开启|关闭" lay-filter="statusSwitch"
                   data-id="{{d.id}}" {{d.status== 1 ? 'checked' : ''}}>
        </script>
        <script type="text/html" id="sortTemplate">
            <input type="number" name="sort" class="layui-input layui-input-inline sort-input"
                   value="{{d.sort}}" data-id="{{d.id}}">
        </script>
        <script type="text/html" id="toolDemo">
            <div class="layui-clear-space">
                <a class="layui-btn layui-btn-xs" lay-event="edit">
                    <i class="layui-icon layui-icon-edit"></i>
                </a>
                <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="delete">
                    <i class="layui-icon layui-icon-delete"></i>
                </a>
            </div>
        </script>
    </div>
</div>
<!--内容主题-->

<!--SCRIPT-->
<?php require_once APP_PATH . '/views/layouts/script.php' ?>
<!--SCRIPT-->
<script>
    layui.use(['table', 'form', 'laydate', 'tabs', 'layer'], function () {
        var table = layui.table;
        var form = layui.form;
        var layer = layui.layer;
        var laydate = layui.laydate;
        var tabs = layui.tabs;
        var $ = layui.$;


        // 初始化表格
        window.tableIns = table.render({
            elem: '#newsTable',
            url: '/api/news-category/list', // 如果使用 AJAX，可填入数据接口 URL
            page: true, // 开启分页
            limit: 10, // 每页显示数量
            limits: [10, 20, 30], // 分页选项
            cols: [[
                {type: 'checkbox'},
                {field: 'id', title: 'ID', width: 150, sort: true},
                {field: 'title', title: '标题'},
                {field: 'status', title: '状态', width: 100, templet: '#statusTemplate'},
                {field: 'sort', title: '排序', width: 160, templet: '#sortTemplate'},
                {field: 'created_at', title: '加入时间', width: 200, sort: true},
                {fixed: 'right', title: '操作', width: 110, templet: '#toolDemo'}
            ]]
        });

        // 日期
        laydate.render({
            elem: '.date'
        });

        // 搜索按钮提交事件
        form.on('submit(search)', function (data) {
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
        $('#btnAdd').click(function () {
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
            var url = id ? '/api/news-category/edit/' + id : '/api/news-category/create';
            var title = id ? '编辑新闻大类' : '添加新闻大类';
            layer.open({
                type: 2,
                title: title,
                area: ['80%', '40%'],
                content: url,
                success: function (layero, index) {
                    // 弹窗加载成功后的回调
                    var iframe = layero.find('iframe')[0];
                    var iframeWin = iframe.contentWindow;

                    // 如果是编辑，可以传递数据到iframe
                    if (id && data) {
                        // 延迟确保iframe加载完成
                        setTimeout(function () {
                            iframeWin.setFormData(data);
                        }, 300);
                    }
                }
            });
        }

        // 状态开关事件
        form.on('switch(statusSwitch)', function (obj) {
            var id = $(this).attr('data-id');
            var status = obj.elem.checked ? 1 : 0;
            $.ajax({
                url: '/api/news-category/updateStatus',
                method: 'POST',
                dataType: 'json',
                data: {id: id, status: status},
                success: function (res) {
                    if (res.code === 0) {
                        layer.msg(res.msg, {icon: 1});
                        tableIns.reload();
                    } else {
                        layer.msg(res.msg, {icon: 2});
                        obj.elem.checked = !obj.elem.checked;
                        form.render('checkbox');
                    }
                },
                error: function () {
                    layer.msg('请求失败', {icon: 2});
                    obj.elem.checked = !obj.elem.checked;
                    form.render('checkbox');
                }
            })
        })

        // 排序输入框事件
        $(document).on('change', '.sort-input', function () {
            var id = $(this).data('id');
            var sort = $(this).val();


            $.ajax({
                url: '/api/news-category/updateSort',
                method: 'POST',
                dataType: 'json',
                data: {id: id, sort: sort},
                success: function (res) {
                    if (res.code === 0) {
                        layer.msg(res.msg, {icon: 1});
                        tableIns.reload();
                    } else {
                        layer.msg(res.msg, {icon: 2});
                    }
                },
                error: function () {
                    layer.msg('请求失败', {icon: 2});
                }
            })
        })

        // 单个删除数据
        function deleteData(id) {
            layer.confirm('确认删除吗', function (index) {
                $.ajax({
                    url: '/api/news-category/delete/' + id,
                    method: 'POST',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code === 0) {
                            layer.msg(res.msg, {icon: 1});
                            tableIns.reload();
                        } else {
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error: function () {
                        layer.msg('请求失败', {icon: 2});
                        layer.close(index);
                    }
                })
            })
        }


        // 批量删除点击按钮事件
        $('#btnBatchDelete').click(function () {
            var checkStatus = table.checkStatus('newsTable');
            var selectedIds = checkStatus.data ? checkStatus.data.map(item => item.id) : [];

            if (selectedIds.length === 0) {
                layer.msg('请至少选择一条记录', {icon: 2});
                return;
            }
            layer.confirm('确定要删除选中的' + selectedIds.length + '条记录吗', function (index) {
                // 发送批量删除请求
                $.ajax({
                    url: '/api/news-category/batchDelete',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({ids: selectedIds}),
                    success: function (res) {
                        if (res.code === 0) {
                            console.log('删除成功');
                            layer.msg(res.msg, {icon: 1});
                            tableIns.reload();
                        } else {
                            layer.msg(res.msg, {icon: 2});
                        }
                    },
                    error: function () {
                        layer.msg('请求失败', {icon: 2})
                    }
                });
                layer.close(index);
            });
        })
    });
</script>






