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
    }
} )