/**
 * 使用示例

模板:
<script type="text/html" id="template">
    <div data-id="{#id#}">
        <p>用户名: {#info.name#}</p>
        <p>生日: {# info.birthday | getDate false | splits '.' #}</p>
        <p>余额: {# info.money | fix this.len '$' #}</p>
    </div>
</script>

调用:

var user = {
    id: 100,
    info: {
        name: '某某',
        birthday: '1980-05-12 10:12:00',
        money: '100'
    },
    len: 2,
    fix: function (val, len, prefix) {
        return prefix + Number(val).toFixed(len);
    },
    getDate: function (val, year) {
        val = String(val).replace(/\s.+$/, '');
        if (year === false) {
            val = val.replace(/\d{4}\-/, '');
        }

        return val;
    },
    splits: function (val, s) {
        return val.replace(/[^\d]/g, s);
    }
};

var html = $.convertTemplate('#template', user, '');
// 如果不使用jquery, 则使用 utils.convertTemplate('模板内容', user, '');
// 第三个参数的作用是: 当定义的标签数据找不到时, 显示的值, 不传则原样显示标签

$('.container').html(html);

渲染结果:
<div data-id="100">
    <p>用户名: 某某</p>
    <p>生日: 05.12</p>
    <p>余额: $100.00</p>
</div>
 **/

(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(factory);
    } else if (typeof exports === 'object') {
        // Node, CommonJS之类的
        module.exports = factory();
    } else {
        // 浏览器全局变量(root 即 window)
        root.utils = factory();

        if (root.jQuery || root.Zepto) {
            var templates = {};
            $.convertTemplate = function (selector, data, fill) {
                var template = templates[selector];
                if (!template) {
                    template = $(selector).html();
                    templates[selector] = template;
                }
                return root.utils.convertTemplate(template, data, fill);
            }
        }
    }
}(this, function () {
    var resolve = function (key) {
        return '["' + key.replace(/\./g, '"]["') + '"]';
    };

    var getValue = function(flag, data) {
        var keys = flag.replace(/^\s+|\s+$/g, '').split(/\s*\|\s*/);
        var key = keys.shift();
        var val = '';
        
        eval('val=data' + resolve(key));

        for (var i=0;i<keys.length;i++) {
            var args = keys[i].split(/\s+/);
            var fn = data[args.shift()];
            
            if (fn && typeof fn === 'function') {
                for (var j=0;j<args.length;j++) {
                    var a = args[j];
                    if (/^this\..+/.test(a)) {
                        a = a.replace('this.', '');
                        a = 'data' + resolve(a);
                    }

                    eval('a=' + a);
                    args[j] = a;
                }
                
                args.unshift(val);
                
                val = fn.apply(null, args);
            }
        }
        
        return val;
    };

    return {
        convertTemplate: function (template, data, fill) {
            return template.replace(/\{#(.+?)#}/g, function ($0, $1) {
                var val = getValue($1, data);
                return typeof val !== 'undefined' ? val : (typeof fill === 'undefined' ? $0 : fill);
            })
        }
    };
}));


String.prototype.toStar = String.prototype.toStar || function (start, length, options) {
        var p1 = this.substr(0, start);
        var p2 = this.substr(start, length);
        var p3 = this.substr(start + length);

        p2 = p2.replace(/./g, '*');

        options = options || {};

        if (typeof options === 'string') {
            options = {
                pad: options
            }
        }

        if (options.pad) {
            options.padLeft = options.pad;
            options.padRight = options.pad;
        } else {
            options.padLeft = options.padLeft || '';
            options.padRight = options.padRight || '';
        }

        p2 = options.padLeft + p2 + options.padRight;

        return p1 + p2 + p3;
    };