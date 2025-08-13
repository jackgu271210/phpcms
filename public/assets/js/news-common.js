layui.use(['table', 'form', 'layer'], function() {
    var table = layui.table;
    var form = layui.form;
    var layer = layui.layer;
    var $ = layui.$;

    // 初始化表格
    var tableIns = table.render({
        elem: '#newsTable',
        url: '/news/list', // 如果使用 AJAX，可填入数据接口 URL
        page: true, // 开启分页
        limit: 5, // 每页显示数量
        limits: [10, 20, 30], // 分页选项
        cols: [[
            {field: 'id', title: 'ID', width: 150, sort: true},
            {field: 'title', title: '标题'},
            {field: 'category_name', title: '类别'},
            {field: 'created_at', title: '加入时间', width: 200, sort: true},
            {fixed: 'right', title:'操作', width: 134, minWidth: 125, templet: '#toolDemo'}
        ]]
    });

    // 搜索
    form.on('submit(formSearch)', function(data) {
        tableIns.reload({
            where: data.field,
            page: {curr:1}
        });
        return false;
    });

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

    // 表单提交
    form.on('submit(formSubmit)', function(data) {
        // var url = data.field.id ? '/news/edit' : '/news/save';

        data.field.content = editor.getHtml();
        $.ajax({
            url: '/news/save',
            type: 'POST',
            data: data.field,
            dataType: 'json',
            success: function(res) {
                if (res.code === 0) {
                    layer.msg(res.msg, {icon:1});
                    layer.closeAll();
                    tableIns.reload();
                } else {
                    layer.msg(res.msg, {icon:2});
                }
            },
            error: function() {
                layer.msg('请求失败', {icon:2});
            }
        });
        return false;
    });

    // 删除数据
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
});