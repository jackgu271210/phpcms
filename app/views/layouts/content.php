<div class="layui-body">
    <!-- 内容主体区域 -->
    <div style="padding: 30px;">
        <div class="layui-card layui-panel">
            <div class="layui-card-header">
                <h2 class="">新闻管理</h2>
            </div>
            <div class="layui-card-body">
                <form class="layui-form" method="POST" action="../../lib/save.php" enctype="multipart/form-data">
                    <div class="layui-form-item">
                        <label class="layui-form-label">标题</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">分类</label>
                        <div class="layui-input-inline">
                            <select name="category_id">
                                <option value="">请选择</option>
                                <option value="AAA">选项 A</option>
                                <option value="BBB">选项 B</option>
                                <option value="CCC">选项 C</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">网页描述</label>
                        <div class="layui-input-block">
                            <textarea name="description"  class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">关键词</label>
                        <div class="layui-input-block">
                            <input type="text" name="keyword" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">新闻内容</label>
                        <div class="layui-input-block">
                            <div id="editor—wrapper">
                                <div id="toolbar-container"><!-- 工具栏 --></div>
                                <div id="editor-container"><!-- 编辑器 --></div>
                            </div>
                            <textarea name="content" id="editor-content" style="display:none;"></textarea>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-row layui-col-space16">
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">内联关键词1</label>
                                <div class="layui-input-block">
                                    <input type="text" name="key1" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label"> 链接1</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url1" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-row layui-col-space16">
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">内联关键词2</label>
                                <div class="layui-input-block">
                                    <input type="text" name="key2" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">链接2</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url2" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-row layui-col-space16">
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">内联关键词3</label>
                                <div class="layui-input-block">
                                    <input type="text" name="key3" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">链接3</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url3" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-row layui-col-space16">
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">内联关键词4</label>
                                <div class="layui-input-block">
                                    <input type="text" name="key4" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">链接4</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url4" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-row layui-col-space16">
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">内联关键词5</label>
                                <div class="layui-input-block">
                                    <input type="text" name="key5" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-col-xs6">
                                <label class="layui-form-label">链接5</label>
                                <div class="layui-input-block">
                                    <input type="text" name="url5" class="layui-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button type="submit" class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <br><br>
    </div>
</div>