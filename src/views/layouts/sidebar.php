<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            <li class="layui-nav-item layui-nav-itemed">
                <a class="" href="javascript:;">新闻管理</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">新闻类别</a></dd>
                    <dd>
                        <a data-url="/news" data-title="新闻管理">
                            <i class="layui-icon layui-icon-list"></i>
                            新闻管理
                        </a>
                    </dd>
                    <dd>
                        <a data-url="/news/add" data-title="添加新闻">
                            <i class="layui-icon layui-icon-add-1"></i>
                            添加新闻
                        </a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">menu group 2</a>
                <dl class="layui-nav-child">
                    <dd><a href="javascript:;">list 1</a></dd>
                    <dd><a href="javascript:;">list 2</a></dd>
                    <dd><a href="javascript:;">超链接</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="javascript:;">click menu item</a></li>
            <li class="layui-nav-item"><a href="javascript:;">the links</a></li>
        </ul>
    </div>
</div>