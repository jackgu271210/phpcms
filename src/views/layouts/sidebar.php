<div class="layui-side layui-bg-black">
    <div class="layui-side-scroll">
        <!-- 左侧导航区域（可配合layui已有的垂直导航） -->
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            <li class="layui-nav-item layui-nav-itemed">
                <a class="" href="javascript:;">产品管理</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a data-url="/product-category" data-title="产品类别列表">
                            <i class="layui-icon layui-icon-list"></i>
                            产品类别列表
                        </a>
                    </dd>
                    <dd>
                        <a data-url="/product" data-title="产品列表">
                            <i class="layui-icon layui-icon-list"></i>
                            产品列表
                        </a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item layui-nav-itemed">
                <a class="" href="javascript:;">新闻管理</a>
                <dl class="layui-nav-child">
                    <dd>
                        <a data-url="/news-category" data-title=" 新闻类别列表">
                            <i class="layui-icon layui-icon-list"></i>
                            新闻类别列表
                        </a>
                    </dd>
                    <dd>
                        <a data-url="/news" data-title="新闻列表">
                            <i class="layui-icon layui-icon-list"></i>
                            新闻列表
                        </a>
                    </dd>
                </dl>
            </li>
            <li class="layui-nav-item"><a href="javascript:;">click menu item</a></li>
            <li class="layui-nav-item"><a href="javascript:;">the links</a></li>
        </ul>
    </div>
</div>