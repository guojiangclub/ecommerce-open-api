/**
 * simplePagination.js v1.6
 * A simple jQuery pagination plugin.
 * http://flaviusmatis.github.com/simplePagination.js/
 *
 * Copyright 2012, Flavius Matis
 * Released under the MIT license.
 * http://flaviusmatis.github.com/license.html
 */

(function($){

    var methods = {
        init: function(options) {
            var o = $.extend({
                items: 1,
                itemsOnPage: 1,
                pages: 0,
                displayedPages: 5,
                edges: 2,
                currentPage: 0,
                hrefTextPrefix: '#page-',
                hrefTextSuffix: '',
                prevText: 'Prev',
                nextText: 'Next',
                ellipseText: '&hellip;',
                ellipsePageSet: true,
                cssStyle: 'light-theme',
                listStyle: '',
                labelMap: [],
                selectOnClick: true,
                nextAtFront: false,
                invertPageOrder: false,
                useStartEdge : true,
                useEndEdge : true,
                onPageClick: function(pageNumber, event) {
                    // Callback triggered when a page is clicked
                    // Page number is given as an optional parameter
                },
                onInit: function() {
                    // Callback triggered immediately after initialization
                }
            }, options || {});

            var self = this;

            o.pages = o.pages ? o.pages : Math.ceil(o.items / o.itemsOnPage) ? Math.ceil(o.items / o.itemsOnPage) : 1;
            if (o.currentPage)
                o.currentPage = o.currentPage - 1;
            else
                o.currentPage = !o.invertPageOrder ? 0 : o.pages - 1;
            o.halfDisplayed = o.displayedPages / 2;

            this.each(function() {
                // self.addClass(o.cssStyle + ' simple-pagination').data('pagination', o);
                self.data('pagination', o);
                methods._draw.call(self);
            });

            o.onInit();

            return this;
        },

        selectPage: function(page) {
            methods._selectPage.call(this, page - 1);
            return this;
        },

        prevPage: function() {
            var o = this.data('pagination');
            if (!o.invertPageOrder) {
                if (o.currentPage > 0) {
                    methods._selectPage.call(this, o.currentPage - 1);
                }
            } else {
                if (o.currentPage < o.pages - 1) {
                    methods._selectPage.call(this, o.currentPage + 1);
                }
            }
            return this;
        },

        nextPage: function() {
            var o = this.data('pagination');
            if (!o.invertPageOrder) {
                if (o.currentPage < o.pages - 1) {
                    methods._selectPage.call(this, o.currentPage + 1);
                }
            } else {
                if (o.currentPage > 0) {
                    methods._selectPage.call(this, o.currentPage - 1);
                }
            }
            return this;
        },

        getPagesCount: function() {
            return this.data('pagination').pages;
        },

        setPagesCount: function(count) {
            this.data('pagination').pages = count;
        },

        getCurrentPage: function () {
            return this.data('pagination').currentPage + 1;
        },

        destroy: function(){
            this.empty();
            return this;
        },

        drawPage: function (page) {
            var o = this.data('pagination');
            o.currentPage = page - 1;
            this.data('pagination', o);
            methods._draw.call(this);
            return this;
        },

        redraw: function(){
            methods._draw.call(this);
            return this;
        },

        disable: function(){
            var o = this.data('pagination');
            o.disabled = true;
            this.data('pagination', o);
            methods._draw.call(this);
            return this;
        },

        enable: function(){
            var o = this.data('pagination');
            o.disabled = false;
            this.data('pagination', o);
            methods._draw.call(this);
            return this;
        },

        updateItems: function (newItems) {
            var o = this.data('pagination');
            o.items = newItems;
            o.pages = methods._getPages(o);
            this.data('pagination', o);
            methods._draw.call(this);
        },

        updateItemsOnPage: function (itemsOnPage) {
            var o = this.data('pagination');
            o.itemsOnPage = itemsOnPage;
            o.pages = methods._getPages(o);
            this.data('pagination', o);
            methods._selectPage.call(this, 0);
            return this;
        },

        getItemsOnPage: function() {
            return this.data('pagination').itemsOnPage;
        },

        _draw: function() {
            var	o = this.data('pagination'),
                interval = methods._getInterval(o),
                i,
                tagName;

            methods.destroy.call(this);

            tagName = (typeof this.prop === 'function') ? this.prop('tagName') : this.attr('tagName');

            var $panel = tagName === 'UL' ? this : $('<ul' + (o.listStyle ? ' class="' + o.listStyle + '"' : '') + '></ul>').appendTo(this);

            // Generate Prev link
            if (o.prevText) {
                methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage - 1 : o.currentPage + 1, {text: o.prevText, classes: 'prev'});
            }

            // Generate Next link (if option set for at front)
            if (o.nextText && o.nextAtFront) {
                methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage + 1 : o.currentPage - 1, {text: o.nextText, classes: 'next'});
            }

            // Generate start edges
            if (!o.invertPageOrder) {
                if (interval.start > 0 && o.edges > 0) {
                    if(o.useStartEdge) {
                        var end = Math.min(o.edges, interval.start);
                        for (i = 0; i < end; i++) {
                            methods._appendItem.call(this, i);
                        }
                    }
                    if (o.edges < interval.start && (interval.start - o.edges != 1)) {
                        $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
                    } else if (interval.start - o.edges == 1) {
                        methods._appendItem.call(this, o.edges);
                    }
                }
            } else {
                if (interval.end < o.pages && o.edges > 0) {
                    if(o.useStartEdge) {
                        var begin = Math.max(o.pages - o.edges, interval.end);
                        for (i = o.pages - 1; i >= begin; i--) {
                            methods._appendItem.call(this, i);
                        }
                    }

                    if (o.pages - o.edges > interval.end && (o.pages - o.edges - interval.end != 1)) {
                        $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
                    } else if (o.pages - o.edges - interval.end == 1) {
                        methods._appendItem.call(this, interval.end);
                    }
                }
            }

            // Generate interval links
            if (!o.invertPageOrder) {
                for (i = interval.start; i < interval.end; i++) {
                    methods._appendItem.call(this, i);
                }
            } else {
                for (i = interval.end - 1; i >= interval.start; i--) {
                    methods._appendItem.call(this, i);
                }
            }

            // Generate end edges
            if (!o.invertPageOrder) {
                if (interval.end < o.pages && o.edges > 0) {
                    if (o.pages - o.edges > interval.end && (o.pages - o.edges - interval.end != 1)) {
                        $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
                    } else if (o.pages - o.edges - interval.end == 1) {
                        methods._appendItem.call(this, interval.end);
                    }
                    if(o.useEndEdge) {
                        var begin = Math.max(o.pages - o.edges, interval.end);
                        for (i = begin; i < o.pages; i++) {
                            methods._appendItem.call(this, i);
                        }
                    }
                }
            } else {
                if (interval.start > 0 && o.edges > 0) {
                    if (o.edges < interval.start && (interval.start - o.edges != 1)) {
                        $panel.append('<li class="disabled"><span class="ellipse">' + o.ellipseText + '</span></li>');
                    } else if (interval.start - o.edges == 1) {
                        methods._appendItem.call(this, o.edges);
                    }

                    if(o.useEndEdge) {
                        var end = Math.min(o.edges, interval.start);
                        for (i = end - 1; i >= 0; i--) {
                            methods._appendItem.call(this, i);
                        }
                    }
                }
            }

            // Generate Next link (unless option is set for at front)
            if (o.nextText && !o.nextAtFront) {
                methods._appendItem.call(this, !o.invertPageOrder ? o.currentPage + 1 : o.currentPage - 1, {text: o.nextText, classes: 'next'});
            }

            if (o.ellipsePageSet && !o.disabled) {
                methods._ellipseClick.call(this, $panel);
            }

        },

        _getPages: function(o) {
            var pages = Math.ceil(o.items / o.itemsOnPage);
            return pages || 1;
        },

        _getInterval: function(o) {
            return {
                start: Math.ceil(o.currentPage > o.halfDisplayed ? Math.max(Math.min(o.currentPage - o.halfDisplayed, (o.pages - o.displayedPages)), 0) : 0),
                end: Math.ceil(o.currentPage > o.halfDisplayed ? Math.min(o.currentPage + o.halfDisplayed, o.pages) : Math.min(o.displayedPages, o.pages))
            };
        },

        _appendItem: function(pageIndex, opts) {
            var self = this, options, $link, o = self.data('pagination'), $linkWrapper = $('<li></li>'), $ul = self.find('ul');

            pageIndex = pageIndex < 0 ? 0 : (pageIndex < o.pages ? pageIndex : o.pages - 1);

            options = {
                text: pageIndex + 1,
                classes: ''
            };

            if (o.labelMap.length && o.labelMap[pageIndex]) {
                options.text = o.labelMap[pageIndex];
            }

            options = $.extend(options, opts || {});

            if (pageIndex == o.currentPage || o.disabled) {
                if (o.disabled || options.classes === 'prev' || options.classes === 'next') {
                    $linkWrapper.addClass('disabled');
                } else {
                    $linkWrapper.addClass('active');
                }
                $link = $('<span class="current">' + (options.text) + '</span>');
            } else {
                if (!o.hrefTextPrefix && !o.hrefTextSuffix) {
                    $link = $('<a href="javascript:;" class="page-link">' + (options.text) + '</a>');
                } else {
                    $link = $('<a href="' + o.hrefTextPrefix + (pageIndex + 1) + o.hrefTextSuffix + '" class="page-link">' + (options.text) + '</a>');
                }

                $link.click(function(event){
                    return methods._selectPage.call(self, pageIndex, event);
                });
            }

            if (options.classes) {
                $link.addClass(options.classes);
            }

            $linkWrapper.append($link);

            if ($ul.length) {
                $ul.append($linkWrapper);
            } else {
                self.append($linkWrapper);
            }
        },

        _selectPage: function(pageIndex, event) {
            var o = this.data('pagination');
            o.currentPage = pageIndex;
            if (o.selectOnClick) {
                methods._draw.call(this);
            }
            return o.onPageClick(pageIndex + 1, event);
        },


        _ellipseClick: function($panel) {
            var self = this,
                o = this.data('pagination'),
                $ellip = $panel.find('.ellipse');
            $ellip.addClass('clickable').parent().removeClass('disabled');
            $ellip.click(function(event) {
                if (!o.disable) {
                    var $this = $(this),
                        val = (parseInt($this.parent().prev().text(), 10) || 0) + 1;
                    $this
                        .html('<input type="number" min="1" max="' + o.pages + '" step="1" value="' + val + '">')
                        .find('input')
                        .focus()
                        .click(function(event) {
                            // prevent input number arrows from bubbling a click event on $ellip
                            event.stopPropagation();
                        })
                        .keyup(function(event) {
                            var val = $(this).val();
                            if (event.which === 13 && val !== '') {
                                // enter to accept
                                if ((val>0)&&(val<=o.pages))
                                    methods._selectPage.call(self, val - 1);
                            } else if (event.which === 27) {
                                // escape to cancel
                                $ellip.empty().html(o.ellipseText);
                            }
                        })
                        .bind('blur', function(event) {
                            var val = $(this).val();
                            if (val !== '') {
                                methods._selectPage.call(self, val - 1);
                            }
                            $ellip.empty().html(o.ellipseText);
                            return false;
                        });
                }
                return false;
            });
        }

    };

    $.fn.pagination = function(method) {

        // Method calling logic
        if (methods[method] && method.charAt(0) != '_') {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' +  method + ' does not exist on jQuery.pagination');
        }

    };

    /**
     * 自动分页
     * @param options
     *      options.url: 请求发送地址
     *      options.name: 分页参数名称, 默认为page
     *      options.page: 初始页码，如果不给定则直接从url上取
     *      options.get: get或者post方法, 比如$.get, $.post, $.http.get
     *      options.body: 当为post请求时, 额外传输的body数据
     *      options.autoUrl: 自动根据分页更新url，默认为true
     *      options.pager: 自定义分页生成方法, 传入page和url两个参数
     *      options.count: 中间显示的分页数量, 默认为5
     *      options.class: 给分页条加的样式名称, 默认为pagination
     *      options.marks: 返回值中分页相关的标记
     *          marks.total: 总页数, 默认为`meta.pagination.total_pages`
     *          marks.index: 当前页, 默认为`meta.pagination.current_page`
     *          marks.data: 数据, 默认为`data`
     *      options.texts: 显示的文本内容
     *          texts.prev: 上一页
     *          texts.next: 下一页
     *
     * @param callback: 成功获取到分页数据时的回调
     */
    $.fn.pages = function(options, callback) {
        if (!$.isPlainObject(options)) {
            return console.error('options参数错误');
        }

        var marks = $.extend({}, {
            total: 'meta.pagination.total_pages',
            index: 'meta.pagination.current_page',
            data: 'data'
        }, options.marks);

        var args = {
            'url': 'string'
        };

        for (var i in args) {
            if (!args.hasOwnProperty(i)) continue;

            var need = args[i];
            var type = typeof options[i];

            if (need !== type) {
                return console.error('参数[' + i + ']不正确: 预期[' + need + '], 实际为[' + type + ']');
            }
        }

        var params = parseUrl();
        var name = options.name || 'page';
        var page = options.page || params[name] || 1;
        var body = options.body;
        var count = options.count;
        var texts = $.extend({}, {
            prev: '<span aria-hidden="true">&laquo;</span>',
            next: '<span aria-hidden="true">&raquo;</span>'
        }, options.texts);

        var get = options.get || $.get;
        var url = pageUrl(options.url, page, name, options.pager);
        var that = this;
        get(url, body, function(ret) {
            var total = parseObject(ret, marks.total);
            var index = parseObject(ret, marks.index);

            if (total > 0) {
                that.pagination({
                    pages: total,
                    displayedPages: count,
                    currentPage: index,
                    listStyle: options.class || 'pagination',
                    prevText: texts.prev,
                    nextText: texts.next,
                    hrefTextPrefix: '',
                    hrefTextSuffix: '',
                    onPageClick: function(page, evt) {
                        if (options.autoUrl !== false) {
                            try{
                                params.page = page;
                                var href = window.location.href.replace(/\?.*$/, '') + stringifyUrl(params);
                                var state = {
                                    title: window.document.title,
                                    url: href
                                };
                                window.history.pushState(state, document.title, href);
                            }catch (e){}
                        }

                        var url = pageUrl(options.url, page, name, options.pager);
                        get(url, body, function(ret) {
                            if (typeof callback === 'function') {
                                callback(parseObject(ret, marks.data));
                            }
                        });
                    }
                });
            }

            if (typeof callback === 'function') {
                callback(parseObject(ret, marks.data));
            }
        });
    };

    // 创建分页url
    function pageUrl(url, page, name, pager) {
        if (typeof pager === 'function') {
            return pager(page, url);
        } else {
            var exp = new RegExp('\\?(.*&)?' + name + '=');
            if (exp.test(url)) {
                var parts = url.split('?');
                var params = parseUrl(parts[1]);
                params[name] = page;
                return parts[0] + stringifyUrl(params);
            } else {
                return url + (!~url.indexOf('?') ? '?' : '&') + name+ '=' + page;
            }
        }
    }

    // 解析Url
    function parseUrl() {
        var url = window.location.search.replace(/^\?/, '');
        var params = {};
        if (url) {
            url.split('&').forEach(function (part) {
                var parts = part.split('=');
                var name = parts.shift();
                var value = parts.join('&');

                if (/\[]$/.test(name)) {
                    name = name.replace(/\[]$/, '');
                    params[name] = params[name] || [];
                    params[name].push(value);
                } else {
                    params[name] = value;
                }
            })
        }

        return params;
    }

    // Url赋值
    function stringifyUrl(params) {
        var urls = [];
        for (var name in params) {
            if (!params.hasOwnProperty(name)) continue;
            if (Object.prototype.toString.call(params[name]) === '[object Array]') {
                params[name].forEach(function (value) {
                    urls.push(name + '[]=' + value);
                });
            } else {
                urls.push(name + '=' + params[name]);
            }
        }

        if (urls.length) {
            return '?' + urls.join('&');
        } else {
            return '';
        }
    }

    // 解析对象的值
    function parseObject(data, mark) {
        var getValue = function(array, obj) {
            var key = array.shift();
            var val = obj[key];

            if (array.length) {
                val = getValue(array, val);
            }

            return val;
        };

        try {
            var arr = mark.split('.');
            return getValue(arr, data);
        } catch (e) {}
    }
})(jQuery);