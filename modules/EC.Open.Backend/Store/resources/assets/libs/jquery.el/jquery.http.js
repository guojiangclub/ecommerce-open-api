(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD (Register as an anonymous module)
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        module.exports = factory(require('jquery'));
    } else {
        // Browser globals
        factory(jQuery);
    }
}(function ($) {
    var cacheStatus = {};

    var HTTP = {
        version: '1.0.2'
    };

    /**
     * 发送ajax请求, 除了$.ajax本身的配置项之外, 其余增加的配置如下
     * @param options
     *      options.uuid: 请求的唯一标记, 添加该标记则在该次请求结束之前无法重复提交该请求
     *      options.loading: 是否显示全屏loading, 默认为true
     *      options.title: 弹框提示的标题内容, 可省
     *      options.buttons: 弹框提示时的按钮文字, 可省
     *      options.alert: 是否弹框, 默认弹框, 如果为false, 则不弹框, 如果为true, 则成功时也会弹框
     *      options.ignore: 是否在status为false时执行成功的回调, 默认为true不执行
     *      options.confirm: 在操作前弹出确认框
     */
    HTTP.ajax = function (options) {
        options = options || {};

        this.init();

        if (options.confirm) {
            var title = options.title || this.title || '确认';
            var message;
            if (typeof options.confirm === 'string') {
                message = options.confirm;
            } else {
                message = options.confirm.message || '';
            }

            var that = this;
            this._confirm('question', title, message, options.buttons, function () {
                that._ajax(options);
            });
        } else {
            this._ajax(options);
        }
    };

    /**
     * 初始化HTTP模块
     * @param options
     *      options.style: 初始化样式
     *      options.html: 初始化模板
     *      options.title: 弹框提示的标题内容, 可省
     *      options.buttons: 弹框提示时的按钮文字, 可省
     *      options.codeKey: 返回内容的code部分的键名
     *      options.statusKey: 返回内容的status部分的键名
     *      options.codeMaps: 对应code的错误提示内容
     *      options.messages: 对应400/500系列状态码的错误提示内容
     */
    HTTP.init = function (options) {
        if (this.initialized) return;
        this.initialized = true;

        options = options || {};

        this.title = options.title || '';
        this.buttons = $.extend({
            submit: '确定',
            cancel: '取消'
        }, options.buttons);
        this.codeKey = options.codeKey || 'code';
        this.statusKey = options.statusKey || 'status';
        this.codeMaps = options.codeMaps || {};
        this.messages = $.extend({
            0: '未知错误!',
            400: '请求失败, 请重试!',
            500: '服务端出错, 请刷新页面后重试!'
        }, options.messages);

        var css = options.style || '.__http_tips .__http_tips_mask{position:fixed;top:0;left:0;width:100%;height:100%;background-color:#666;opacity:.4;z-index:99998}.__http_tips .__http_alert_box{position:fixed;left:50%;top:50%;background-color:#fff;z-index:99999;width:360px;margin-left:-180px;margin-top:-120px;border-radius:5px;color:#535e66}.__http_tips .__http_alert_head{font-size:16px;font-weight:bolder;height:50px;line-height:30px;padding:10px;border-bottom:solid 1px #eef0f1}.__http_tips .__http_alert_body{font-size:14px;height:100px;line-height:20px;padding:10px}.__http_tips .__http_alert_foot{text-align:right;font-size:15px;height:50px;line-height:30px;padding:10px;border-top:solid 1px #eef0f1}.__http_tips .__http_alert_button{display:inline-block;border-radius:3px;color:#fff;width:65px;text-align:center;margin-left:10px;cursor:pointer}.__http_tips .__button_success{background-color:#5cb85c}.__http_tips .__button_cancel{background-color:#adadad}.__http_tips .__http_alert_text{height:80px;vertical-align:middle;display:table-cell}.__http_tips .__http_alert_icon{float:left;margin:15px 20px 15px 5px;width:48px;height:48px;background-image:url("data:image/png;base64,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");background-repeat:no-repeat}.__http_tips .__icon_info{background-position:0 0}.__http_tips .__icon_question{background-position:-48px 0}.__http_tips .__icon_success{background-position:0 -48px}.__http_tips .__icon_error{background-position:-48px -48px}.__http_tips .__icon_warn{background-position:0 -96px}.__http_tips .__http_load_box{position:fixed;left:50%;top:50%;width:100px;height:100px;margin-left:-50px;margin-top:-80px;transform:scale(0.5);animation-name:rotateThis;animation-duration:2s;animation-iteration-count:infinite;animation-timing-function:linear;-webkit-animation-name:rotateThis;-webkit-animation-duration:2s;-webkit-animation-iteration-count:infinite;-webkit-animation-timing-function:linear;-moz-animation-name:rotateThis;-moz-animation-duration:2s;-moz-animation-iteration-count:infinite;-moz-animation-timing-function:linear;-o-animation-name:rotateThis;-o-animation-duration:2s;-o-animation-iteration-count:infinite;-o-animation-timing-function:linear}.__http_tips .__http_load_box div{width:10px;height:30px;background:#000;position:absolute;top:35px;left:45px;margin:0}.__http_tips .__http_load_bar1{transform:rotate(0deg) translate(0,-40px);opacity:.12}.__http_tips .__http_load_bar2{transform:rotate(45deg) translate(0,-40px);opacity:.25}.__http_tips .__http_load_bar3{transform:rotate(90deg) translate(0,-40px);opacity:.37}.__http_tips .__http_load_bar4{transform:rotate(135deg) translate(0,-40px);opacity:.50}.__http_tips .__http_load_bar5{transform:rotate(180deg) translate(0,-40px);opacity:.62}.__http_tips .__http_load_bar6{transform:rotate(225deg) translate(0,-40px);opacity:.75}.__http_tips .__http_load_bar7{transform:rotate(270deg) translate(0,-40px);opacity:.87}.__http_tips .__http_load_bar8{transform:rotate(315deg) translate(0,-40px);opacity:1}@keyframes rotateThis{from{-webkit-transform:scale(0.5) rotate(0deg)}to{-webkit-transform:scale(0.5) rotate(360deg)}}@-webkit-keyframes rotateThis{from{-webkit-transform:scale(0.5) rotate(0deg)}to{-webkit-transform:scale(0.5) rotate(360deg)}}@-moz-keyframes rotateThis{from{-webkit-transform:scale(0.5) rotate(0deg)}to{-webkit-transform:scale(0.5) rotate(360deg)}}@-o-keyframes rotateThis{from{-webkit-transform:scale(0.5) rotate(0deg)}to{-webkit-transform:scale(0.5) rotate(360deg)}}';

        var style = document.createElement('style');
        style.type = 'text/css';
        style.innerHTML = css;
        document.getElementsByTagName('head').item(0).appendChild(style);

        var html = options.html || [
                '<div class="__http_tips" style="display: none">',
                '    <div class="__http_tips_mask"></div>',
                '    <div class="__http_alert_box">',
                '        <div class="__http_alert_head"></div>',
                '        <div class="__http_alert_body">',
                '            <span class="__http_alert_icon __icon_error"></span>',
                '            <div class="__http_alert_text"></div>',
                '        </div>',
                '        <div class="__http_alert_foot">',
                '            <div class="__http_alert_button __http_button_cancel __button_cancel">' + this.buttons.cancel + '</div>',
                '            <div class="__http_alert_button __http_button_submit __button_success">' + this.buttons.submit + '</div>',
                '        </div>',
                '    </div>',
                '    <div class="__http_load_box">',
                '        <div class="__http_load_bar1"></div>',
                '        <div class="__http_load_bar2"></div>',
                '        <div class="__http_load_bar3"></div>',
                '        <div class="__http_load_bar4"></div>',
                '        <div class="__http_load_bar5"></div>',
                '        <div class="__http_load_bar6"></div>',
                '        <div class="__http_load_bar7"></div>',
                '        <div class="__http_load_bar8"></div>',
                '    </div>',
                '</div>'
            ].join('');

        $('body').append(html);

        this.$__tips = $('.__http_tips');
        this.$__alert = $('.__http_alert_box');
        this.$__icons = $('.__http_alert_icon');
        this.$__loading = $('.__http_load_box');
        this.$__title = $('.__http_alert_head');
        this.$__text = $('.__http_alert_text');
        this.$__cancel = $('.__http_button_cancel');
        this.$__submit = $('.__http_button_submit');

        var that = this;
        this.$__cancel.on('click', function () {
            executeFunction(that._fallback, that._context);
            that._close();
        });
        this.$__submit.on('click', function () {
            that._close();
            executeFunction(that._callback, that._context);
        });

        this.success = function (message, title) {
            this._alert('success', title || '成功', message);
        };

        this.error = function (message, title) {
            this._alert('error', title || '错误', message);
        };

        this.warn = function (message, title) {
            this._alert('warn', title || '警告', message);
        };

        this.info = function (message, title) {
            this._alert('info', title || '提示', message);
        };

        this.confirm = function (message, title, callback, fallback, buttons) {
            if (title && typeof title === 'function') {
                buttons = fallback;
                fallback = callback;
                callback = title;
                title = '确认';
            }
            if (fallback && typeof fallback === 'object') {
                buttons = fallback;
                fallback = null;
            }
            this._confirm('question', title || '确认', message, buttons, callback, fallback);
        };

        this._alert = function (status, title, message, buttons, callback, context) {
            var _buttons = {
                cancel: null
            };
            if (typeof buttons === 'string') {
                _buttons.submit = buttons || null;
            } else {
                _buttons.submit = buttons;
            }

            this._confirm(status, title, message, _buttons, callback, null, context);
        };

        this._confirm = function (status, title, message, buttons, callback, fallback, context) {
            // status can be: error, warn, success, info, question
            var clsName = '__icon_' + status;

            buttons = $.extend({}, this.buttons, buttons);

            this.$__icons.removeClass().addClass('__http_alert_icon ' + clsName);
            this.$__title.text(title);
            this.$__text.text(message);
            this.$__submit.text(buttons.submit);

            if (buttons.cancel === null) {
                this.$__cancel.hide();
            } else {
                this.$__cancel.text(buttons.cancel);
                this.$__cancel.show();
            }

            this.$__loading.hide();
            this.$__alert.show();
            this.$__tips.show();

            this._callback = callback;
            this._fallback = fallback;
            this._context = context;
        };

        this._loading = function () {
            this.$__alert.hide();
            this.$__loading.show();
            this.$__tips.show();
        };

        this._close = function () {
            this.$__tips.hide();
        };
    };

    HTTP.get = function (url, data, fn, options) {
        this.ajax(defineOptions('GET', url, data, fn, options));
    };

    HTTP.post = function (url, data, fn, options) {
        this.ajax(defineOptions('POST', url, data, fn, options));
    };

    HTTP.put = function (url, data, fn, options) {
        this.ajax(defineOptions('PUT', url, data, fn, options));
    };

    HTTP.delete = function (url, data, fn, options) {
        this.ajax(defineOptions('DELETE', url, data, fn, options));
    };

    ['success', 'error', 'warn', 'info', 'confirm'].forEach(function (key) {
        HTTP[key] = function () {
            if (!HTTP.initialized) {
                HTTP.init();
                HTTP[key].apply(HTTP, arguments);
            }
        }
    });

    // 实际的请求操作
    HTTP._ajax = function (options) {
        var opts = {};
        var uuid = options.uuid;

        if (uuid) {
            if (cacheStatus[uuid]) return;
            cacheStatus[uuid] = true;
        }

        if (options.success) {
            opts.success = options.success;
            delete options.success;
        }

        if (options.error) {
            opts.error = options.error;
            delete options.error;
        }

        if (options.complete) {
            opts.complete = options.complete;
            delete options.complete;
        }

        if (options.beforeSend) {
            opts.beforeSend = options.beforeSend;
            delete options.beforeSend;
        }

        var that = this;
        var context = options.context;
        options.beforeSend = function (xhr, settings) {
            var ret;
            if (typeof opts.beforeSend === 'function') {
                ret = executeFunction(opts.beforeSend, context, xhr, settings);
            }

            if (ret === false) {
                return false;
            } else {
                if (options.loading !== false) {
                    that._loading();
                }
            }
        };
        options.complete = function (xhr, statusText) {
            that._close();

            if (uuid && cacheStatus[uuid]) {
                delete cacheStatus[uuid];
            }

            var title, message;
            var status = xhr.status;
            var response = xhr.responseJSON;
            var isSuccess = status >= 200 && status < 300 || status === 304;

            if (isSuccess && response) {
                if (response[that.statusKey]) {
                    // 成功时的回调
                    if (options.alert === true) {
                        title = options.title || that.title || '提示';
                        message = that.codeMaps[response[that.codeKey]] || response.message || '操作成功。';
                        that._alert('success', title, message, options.buttons, function () {
                            executeFunction(opts.success, context, response, statusText, xhr);
                            executeFunction(opts.complete, context, xhr, statusText);
                        });
                    } else {
                        executeFunction(opts.success, context, response, statusText, xhr);
                        executeFunction(opts.complete, context, xhr, statusText);
                    }
                } else {
                    // 失败时的弹框
                    if (options.alert !== false) {
                        title = options.title || that.title || '提示';
                        message = that.codeMaps[response[that.codeKey]] || response.message || '请求成功, 但是没有返回预期的结果。';
                        that._alert('warn', title, message, options.buttons, function () {
                            if (options.ignore === false) {
                                executeFunction(opts.success, context, response, statusText, xhr);
                            }
                            executeFunction(opts.complete, context, xhr, statusText);
                        });
                    } else {
                        if (options.ignore === false) {
                            executeFunction(opts.success, context, response, statusText, xhr);
                        }
                        executeFunction(opts.complete, context, xhr, statusText);
                    }
                }
            } else {
                // 错误时的弹框
                title = options.title || that.title || '错误';
                message = queryMessage(status, that.messages);    // TODO
                that._alert('error', title, message, options.buttons, function () {
                    executeFunction(opts.error, context, xhr, statusText);
                    executeFunction(opts.complete, context, xhr, statusText);
                });
            }
        };

        $.ajax(options);
    };

    function defineOptions(method, url, data, fn, options) {
        if (typeof data === 'function') {
            options = fn;
            fn = data;
            data = null;
        }

        if ($.isPlainObject(fn)) {
            options = fn;
            fn = null;
        }

        options = options || {};

        if (typeof fn === 'function') {
            options.success = fn;
        }

        if (data !== null && typeof data !== 'undefined') {
            options.data = data;
        }

        options.url = url;
        options.type = method;

        return options;
    }

    function executeFunction(fn, context) {
        if (fn && typeof fn === 'function') {
            var args = Array.prototype.slice.call(arguments, 2);
            return fn.apply(context || HTTP, args);
        }
    }

    function queryMessage(status, messages) {
        status = String(status) || '';
        var len = status.length;
        for (var i=len; i>0; i--) {
            var chars = status.substring(0, i) + repeatZero(len - i);
            if (typeof messages[chars] !== 'undefined') {
                return messages[chars];
            }
        }

        return messages[0];
    }

    function repeatZero(count) {
        count = parseInt(count) || 0;
        var zeros = [];
        for (var i=0;i<count;i++) zeros.push(0);
        return zeros.join('');
    }

    $.http = HTTP;
}));