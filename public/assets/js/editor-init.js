// 初始化单个编辑器
function initEditor(wrapperId, toolbarId, editorId, contentId, initialContent, config= {}, $) {
    const { createEditor, createToolbar } = window.wangEditor;

    // 默认配置
    const defaultConfig = {
        placeholder: '请输入内容',
        onChange(editor) {
            $(`#${contentId}`).val(editor.getHtml());
        },
        MENU_CONF: {
            uploadImage: {
                server: '/upload',
                fieldName: 'file',
                allowedFileTypes: ['image/*'],
                maxFileSize: 20 * 1024 * 1024, // 20 MB
                customInsert(res, insertFn) {
                    if (res.errno !== 0) {
                        parent.layer.msg(res.message, {icon:2});
                        return;
                    }
                    insertFn(res.data.url);
                }
            }
        }
    };

    // 合并默认配置和自定义配置
    const editorConfig = { ...defaultConfig, ...config };

    // 销毁已有实例（如果存在）
    if(window[`editor_${contentId}`]) {
        window[`editor_${contentId}`].destroy();
    }

    // 创建编辑器实例
    window[`editor_${contentId}`] = createEditor({
        selector: `#${editorId}`,
        html: initialContent || '',
        config: editorConfig,
        mode: 'default'
    });

    // 创建工具栏
    createToolbar({
        editor: window[`editor_${contentId}`],
        selector: `#${toolbarId}`,
        config: {},
        mode: 'default'
    });
}

// 初始化多个编辑器
function initEditors(editorsConfig, $) {
    editorsConfig.forEach(config => {
        initEditor(
            config.wrapperId,
            config.toolbarId,
            config.editorId,
            config.contentId,
            $(`#${config.contentId}`).val() || '',
            config.customConfig || {},
            $
        );
    });
}

// 导出函数（如果使用模块化）
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { initEditor, initEditors };
}



