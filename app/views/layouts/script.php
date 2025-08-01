<script src="layui/layui.js"></script>
<script src="wangeditor/js/index.js"></script>
<script>
    const { createEditor, createToolbar } = window.wangEditor

    const editorConfig = {
        placeholder: 'Type here...',
        onChange(editor) {
            const html = editor.getHtml();
            document.getElementById('editor-content').value = html; // 将内容同步到 textarea
            console.log('editor content', html)
            // 也可以同步到 <textarea>
        },
        MENU_CONF: {
            uploadImage: {
                server: 'upload.php',
                fieldName: 'file',
                maxFileSize: 20 * 1024 * 1024, // 5MB
                allowedFileTypes: ['image/*'],
                customInsert(res, insertFn) {
                    // console.log(res);
                    if (res.errno == 1) {
                        alert(res.message);
                        return;
                    }
                    insertFn(res.data.url);
                }
            }
        }
    }

    const editor = createEditor({
        selector: '#editor-container',
        html: '<p><br></p>',
        config: editorConfig,
        mode: 'default', // or 'simple'
    })

    const toolbarConfig = {}

    const toolbar = createToolbar({
        editor,
        selector: '#toolbar-container',
        config: toolbarConfig,
        mode: 'default', // or 'simple'
    })

    // Initialize Layui form
    layui.use(['form'], function () {
        var form = layui.form;
        // Optional: Handle form submission
        form.on('submit(formSubmit)', function (data) {
            console.log('Form data:', data.field);
            return true; // Allow form submission
        });
    });
</script>


</body>
</html>