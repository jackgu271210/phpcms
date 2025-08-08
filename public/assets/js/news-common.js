function initializeNewsModule(options = {}) {
    layui.use(['table', 'jquery', 'form'], function() {
        var table = layui.table;
        var $ = layui.jquery;
        var form = layui.form;
        var layer = layui.layer;

        // Default options
        var defaults = {
            tableId: 'newsTable',
            editUrl: '/news/edit/',
            saveUrl: '/news/save',
            deleteUrl: '/news/delete',
            formFilter: 'demo1',
            onEditorInit: null // Callback for custom editor initialization
        };
        var config = $.extend({}, defaults, options);

        // Form submission
        form.on('submit(' + config.formFilter + ')', function(data) {
            layer.load(1); // Show loading animation
            fetch(config.saveUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data.field)
            })
                .then(response => response.json())
                .then(res => {
                    layer.closeAll('loading');
                    if (res.code === 0) {
                        layer.msg('保存成功', { icon: 1 });
                        layer.closeAll();
                        table.reload(config.tableId);
                    } else {
                        layer.msg('保存失败: ' + res.msg, { icon: 2 });
                    }
                })
                .catch(error => {
                    layer.closeAll('loading');
                    layer.msg('请求失败: ' + error, { icon: 2 });
                });
            return false; // Prevent default submission
        });

        // Table event handling
        table.on('tool(' + config.tableId + ')', function(obj) {
            var data = obj.data;
            var id = obj.data.id;
            if (obj.event === 'edit') {
                fetch(config.editUrl + id)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('网络响应失败！');
                        }
                        return response.text();
                    })
                    .then(html => {
                        layer.open({
                            type: 1,
                            title: '编辑新闻',
                            area: ['80%', '80%'],
                            content: html,
                            success: function(layero, index) {
                                // Check editor containers
                                if (layero.find('#editor-container').length === 0 || layero.find('#toolbar-container').length === 0) {
                                    console.error('Editor containers not found in popup');
                                    layer.msg('编辑器容器未找到', { icon: 2 });
                                    return;
                                }

                                // Ensure wangEditor is loaded
                                if (typeof window.wangEditor === 'undefined') {
                                    console.error('wangEditor not loaded');
                                    layer.msg('编辑器脚本未加载', { icon: 2 });
                                    return;
                                }

                                // Initialize editor with delay
                                setTimeout(() => {
                                    if (typeof window.initEditor === 'function') {
                                        window.initEditor();
                                        console.log('initEditor called');
                                    } else {
                                        console.error('initEditor function not defined');
                                        layer.msg('编辑器初始化失败', { icon: 2 });
                                    }
                                    form.render();
                                    if (config.onEditorInit) {
                                        config.onEditorInit(layero, index);
                                    }
                                }, 100);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching edit form:', error);
                        layer.msg('加载编辑表单失败: ' + error, { icon: 2 });
                    });
            } else if (obj.event === 'delete') {
                layer.confirm('确定删除吗？', function(index) {
                    fetch(config.deleteUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    })
                        .then(response => response.json())
                        .then(res => {
                            if (res.code === 0) {
                                layer.msg(res.msg, { icon: 1 });
                                table.reload(config.tableId);
                                layer.close(index);
                            } else {
                                layer.msg(res.msg, { icon: 2 });
                            }
                        })
                        .catch(err => {
                            layer.msg('请求失败: ' + err, { icon: 2 });
                        });
                }, function(index) {
                    layer.close(index);
                });
            }
        });
    });
}

function initEditor() {
    console.log('initEditor function started');
    if (!window.wangEditor) {
        console.error('wangEditor is not loaded.');
        layui.layer.msg('编辑器加载失败，请检查网络或脚本路径', { icon: 2 });
        return;
    }

    const { createEditor, createToolbar } = window.wangEditor;
    console.log('Editor containers:', document.getElementById('editor-container'), document.getElementById('toolbar-container'));

    const editorConfig = {
        placeholder: '请输入内容...',
        onChange(editor) {
            const html = editor.getHtml();
            document.getElementById('editor-content').value = html;
            console.log('Editor content updated:', html);
        },
        MENU_CONF: {
            uploadImage: {
                server: '/upload.php',
                fieldName: 'file',
                maxFileSize: 20 * 1024 * 1024,
                allowedFileTypes: ['image/*'],
                customInsert(res, insertFn) {
                    if (res.errno === 1) {
                        layui.layer.msg(res.message, { icon: 2 });
                        return;
                    }
                    insertFn(res.data.url);
                }
            }
        }
    };

    const editor = createEditor({
        selector: '#editor-container',
        html: document.getElementById('editor-content').value || '<p><br></p>',
        config: editorConfig,
        mode: 'default'
    });

    const toolbarConfig = {};

    const toolbar = createToolbar({
        editor,
        selector: '#toolbar-container',
        config: toolbarConfig,
        mode: 'default'
    });

    window.wangEditor._editor = editor;
    console.log('wangEditor initialized');
}