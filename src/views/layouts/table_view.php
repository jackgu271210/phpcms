<!-- views/layouts/table_view.php -->
<?php require_once APP_PATH . '/views/layouts/style.php'; ?>
<div class="layui-card layui-panel">
    <div class="layui-card-body">
        <div class="layui-table-view layui-block layui-form layui-border-box">
            <div class="layui-row layui-table-tool">
                <div style="float:left">
                    <button class="layui-btn" id="btnAdd">
                        <i class="layui-icon layui-icon-add-1"></i> 新增
                    </button>
                    <button class="layui-btn layui-btn-danger" id="btnBatchDelete">
                        <i class="layui-icon layui-icon-delete"></i> 批量删除
                    </button>
                    <?php if (!empty($showCategoryFilter)): ?>
                        <div class="category-select layui-inline">
                            <div class="layui-input-wrap">
                                <select name="category_id" lay-filter="category-filter">
                                    <option value="">所有数据</option>
                                    <!-- 动态分类数据将通过 JS 提供 -->
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>
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
        <table id="<?php echo htmlspecialchars($tableId); ?>" lay-filter="<?php echo htmlspecialchars($tableFilter); ?>"></table>
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