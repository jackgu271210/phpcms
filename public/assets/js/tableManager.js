layui.define(['table', 'form', 'laydate', 'layer' ], function(exports) {
    var table = layui.table;
    var form = layui.form;
    var layer = layui.layer;
    var laydate = layui.laydate;
    var $ = layui.$;

    function initTable(config) {
        // 默认配置
        var defaultConfig = {
            tableId: 'dataTable',
            tableFilter: 'dataTable',
            apiBaseUrl: '/api/data',
            cols: [[]],
            page: true,
            limit: 10,
            limits: [10, 20, 30],
            showCategoryFilter: false,
            categoryApi: null,
            modalSize: ['80%', '80%'],
            modalTitleAdd: '添加数据',
            modalTitleEdit: '编辑数据',
            hasCategoryColumn: false
        };

        // 合并配置
        var cfg = $.extend(true, {}, defaultConfig, config);

        // 初始化表格
        window.tableIns = table.render({
            elem: '#' + cfg.tableId,
            url: cfg.apiBaseUrl + '/list',
            page: cfg.page,
            limit: cfg.limit,
            limits: cfg.limits,
            cols: [cfg.cols]
        });

        // 日期选择器
        laydate.render({
            elem: '.date'
        });

        // 加载分类下拉框（如果启用）
        function loadCategories() {
            if (!cfg.showCategoryFilter || !cfg.categoryApi) return;
            $.ajax({
                url: cfg.categoryApi,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.code === 0) {
                        var html = '<option value="">所有数据</option>';
                        $.each(res.data, function(index, item) {
                            html += '<option value=" ' + item.id +' ">' + item.title + '</option>';
                        });
                        $('select[name="category_id"]').html(html);
                        form.render('select');
                    } else {
                        layer.msg('分类加载失败', {icon :2});
                    }
                },
                error: function() {
                    layer.msg('网络错误', {icon:2});
                }
            });
        }

        // 初始化分类
        loadCategories();

        // 分类筛选事件
        if (cfg.showCategoryFilter) {
            form.on('select(category-filter)', function(data) {
                var where = {};
                if (data.value) {
                    where.category_id = data.value;
                }
                tableIns.reload({
                    where: where,
                    page: { curr:1 }
                });
            });
        }

        // 搜索事件
        form.on('submit(search)', function(data) {
            var where = {};
            if (data.field.start_date) where.start_date = data.field.start_date;
            if (data.field.end_date) where.end_date = data.field.end_date;
            if (data.field.keyword) where.keyword = data.field.keyword;

            tableIns.reload({
                where: where,
                page: { curr: 1 }
            });
            return false;
        });

        // 添加按钮
        $('#btnAdd').click(function() {
            showForm();
        })

        // 工具条事件
        table.on('tool(' + cfg.tableFilter + ')', function(obj) {
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
            var url = id ? cfg.apiBaseUrl + '/edit/' + id : cfg.apiBaseUrl + '/create';
            var title = id ? cfg.modalTitleEdit : cfg.modalTitleAdd;
            layer.open({
                type: 2,
                title: title,
                area: cfg.modalSize,
                content: url,
                success: function(layero, index) {
                    var iframe = layero.find('iframe')[0];
                    var iframeWin = iframe.contentWindow;
                    if (id && data) {
                        setTimeout(function() {
                            iframeWin.setFormData(data);
                        }, 300)
                    }

                }
            });
        }

        // 状态开关事件
        form.on('switch(statusSwitch)', function(obj) {
           var id = $(this).attr('data-id');
           var status = obj.elem.checked ? 1 : 0;
           $.ajax({
              url: cfg.apiBaseUrl + '/updateStatus',
               method: 'POST',
               dataType: 'json',
               data: { id: id, status: status },
               success: function(res) {
                  if (res.code === 0) {
                      layer.msg(res.msg, {icon:1});
                      tableIns.reload();
                  } else {
                      layer.msg(res.msg, {icon:2});
                      obj.elem.checked = !obj.elem.checked;
                      form.render('checkbox');
                  }
               },
               error: function() {
                  layer.msg('请求失败', {icon:2});
                  obj.elem.checked = !obj.elem.checked;
                  form.render('checkbox');
               }
           });
        });


        // 排序输入框事件
        $(document).on('change', '.sort-input', function() {
            var id = $(this).data('id');
            var sort = $(this).val();
            $.ajax({
                url: cfg.apiBaseUrl + '/updateSort',
                method: 'POST',
                dataType: 'json',
                data: { id:id, sort:sort },
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
                }
            });
        });

        // 单个删除
        function deleteData(id) {
            layer.confirm('确认要删除吗?', function(index) {
               $.ajax({
                   url: cfg.apiBaseUrl + '/delete/' + id,
                   method: 'POST',
                   dataType: 'json',
                   success: function(res) {
                       if (res.code === 0) {
                           layer.msg(res.msg, {icon:1});
                           tableIns.reload();
                       } else {
                           layer.msg(res.msg, {icon:2});
                       }
                       layer.close(index);
                   },
                   error: function() {
                       layer.msg('请求失败', {icon:2});
                       layer.close(index);
                   }
               });
            });
        }

        // 批量删除
        $('#btnBatchDelete').click(function() {
           var checkStatus = table.checkStatus(cfg.tableId);
           var selectedIds = checkStatus.data ? checkStatus.data.map(item => item.id) : [];
           if (selectedIds.length === 0) {
               layer.msg('请至少选择一条记录', {icon:2});
               return;
           }
           layer.confirm('确认要删除选中的' + selectedIds.length + '条记录吗?', function(index) {
               $.ajax({
                  url: cfg.apiBaseUrl + '/batchDelete',
                   type: 'POST',
                   contentType: 'application/json',
                   data: JSON.stringify({ ids: selectedIds }),
                   success: function(res) {
                      if (res.code === 0) {
                          layer.msg(res.msg, {icon:1});
                          tableIns.reload();
                      } else {
                          layer.msg(res.msg, {icon:2});
                      }
                      layer.close(index);
                   },
                   error: function() {
                      layer.msg('请求失败', {icon:2});
                      layer.close(index);
                   }
               });
            });
        });
    }

    exports('tableManager', {initTable: initTable});
} )