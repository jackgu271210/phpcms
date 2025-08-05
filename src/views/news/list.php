
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
            url: '', // 如果使用 AJAX，可填入数据接口 URL
            data: <?php echo json_encode($response['data']); ?>, // 直接使用 PHP 传递的数据
            page: true, // 开启分页
            limit: <?php echo $response['count'] > 0 ? $response['limit'] : 10; ?>, // 每页显示数量
            limits: [10, 20, 30], // 分页选项
            cols: [[
                {field: 'id', title: 'ID', width: 80, sort: true},
                {field: 'title', title: 'Title', width: 300},
                {field: 'created_at', title: 'Created At', width: 200, sort: true}
            ]],
            totalRow: false // 是否显示总计行
        });
    });
</script>


