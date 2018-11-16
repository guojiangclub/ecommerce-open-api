/**
 * jQuery.upload
 * A simple jQuery upload plugin.
 */

(function($){
    if (typeof $ !== 'function') {
        return console.error('缺少jQuery对象');
    }
    
    if (typeof $.fn.ajaxSubmit !== 'function') {
        return console.error('缺少jQuery.form对象');
    }

    /**
     * 单个文件上传
     * @param options
     *      options.id: 可省略, 默认为一个随机字符串
     *      options.url: 上传地址
     *      options.success: 上传成功时候的回调
     *      options.error: 上传失败时候的回调
     */
    $.fn.upload = function(options) {
        this.on('change', function() {
            var id = options.id || 'upload-' + Math.random().toString(36).substr(2, 12);
            var el = $(this);
            el.wrap('<form id="' + id + '" action="' + options.url + '" method="post" enctype="multipart/form-data"></form>');
            var form = $('#' + id);

            var opts = {};
            if (typeof options.success === 'function') {
                opts.success = options.success;
                delete options.success;
            }

            if (typeof options.error === 'function') {
                opts.error = options.error;
                delete options.error;
            }

            options.success = function() {
                var newInput = el.clone(true);
                newInput.insertAfter(form);
                newInput.val('');
                form.remove();

                if (opts.success) {
                    opts.success.apply(this, arguments);
                }
            };

            options.error = function() {
                var newInput = el.clone(true);
                newInput.insertAfter(form);
                form.remove();

                if (opts.error) {
                    opts.error.apply(this, arguments);
                }
            };

            form.ajaxSubmit(options);
        });
    };
})(jQuery);