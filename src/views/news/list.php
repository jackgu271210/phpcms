<!-- news/list.php -->
<?php
$showCategoryFilter = true;
$tableId = 'newsTable';
$tableFilter = 'newsTable';
require_once APP_PATH . '/views/layouts/table_view.php';
?>
<?php require_once APP_PATH . '/views/layouts/script.php'; ?>
<script>
    layui.use(['tableManager'], function() {
        var tableManager = layui.tableManager;
        tableManager.initTable({
            tableId: '<?php echo $tableId; ?>',
            tableFilter: '<?php echo $tableFilter; ?>',
            apiBaseUrl: '/api/news',
            cols: [
                {type: 'checkbox'},
                {field: 'id', title: 'ID', width: 150, sort: true},
                {field: 'title', title: '标题'},
                {field: 'category_name', title: '类别'},
                {field: 'status', title: '状态', width: 100, templet: '#statusTemplate'},
                {field: 'sort', title: '排序', width: 160, templet: '#sortTemplate'},
                {field: 'created_at', title: '加入时间', width: 200, sort: true},
                {fixed: 'right', title: '操作', width: 110, templet: '#toolDemo'}
            ],
            showCategoryFilter: true,
            categoryApi: '/api/news/categories',
            modalSize: ['80%', '80%'],
            modalTitleAdd: '添加新闻',
            modalTitleEdit: '编辑新闻'
        });
    });
</script>