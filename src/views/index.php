
<!--头部-->
<?php require_once APP_PATH . '/views/layouts/header.php' ?>
<!--头部-->

<!--侧边栏-->
<?php require_once APP_PATH . '/views/layouts/sidebar.php' ?>
<!--侧边栏-->

<!--内容主题-->
<div class="layui-body">
    <div id="main-tabs">
    </div>
</div>

<!--底部-->
<?php require_once APP_PATH . '/views/layouts/footer.php' ?>
<!--底部-->
<script>
    layui.use([ 'tabs', 'jquery'], function() {
        var tabs = layui.tabs;
        var $ = layui.$;

        // 初始化选项卡
        var mainTab = tabs.render({
            elem: '#main-tabs',
            closable: true,
            header: [
            ],
            body: [
            ],
            index: 0, // 初始选中标签索引
        });

        // 侧边栏点击事件
        $('body').on('click', '.layui-nav-tree a[data-url]', function(e) {
           e.preventDefault();

           var url = $(this).data('url');
            console.log(url);
           var title = $(this).data('title') || '新页面';

           // 打开选项卡
            openTab(title, url);
        });

        // 打开选项卡函数
        function openTab(title, url) {
            var tabId = 'tab_' + Math.random().toString(36).substr(2);

            // 检查是否已存在相同标题的选项卡
            var exists = false;
            $('.layui-tabs-header li').each(function() {
                var tabTitle = $(this).text().trim();
                if (tabTitle === title) {
                    exists = true;
                    var existTabId = $(this).attr('lay-id');
                    // 切换到已存在的选项卡
                    tabs.change('main-tabs', existTabId);
                    return false;
                }
            });

            if (!exists) {
                // 添加新选项卡
                tabs.add('main-tabs', {
                    id: tabId,
                    title: title,
                    content: '<iframe src=" ' +url+ ' " frameborder="0" class="layui-frame"></iframe>'
                });
                // 切换到新选项卡
                // tabs.change('main-tabs', tabId);
            }
        }

    });
</script>






