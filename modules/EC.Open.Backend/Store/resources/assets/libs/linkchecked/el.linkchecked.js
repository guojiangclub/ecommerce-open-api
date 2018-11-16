(function ($) {
    String.prototype.startsWith = String.prototype.startsWith || function (chars) {
        return this.substr(0, chars.length) === chars;
    };

    $.iCheckAll = function (options, nameKey, rootDom) {
        options = options || {};
        options.prefix = options.prefix || 'icheck';
        options.seprator = options.seprator || '_';
        rootDom = rootDom || $(document);
        nameKey = nameKey || 'icheck-name';
        var prefix = options.prefix;
        var seprator = options.seprator;
        var allCheckbox = rootDom.find('input[' + nameKey + '^=' + prefix + ']:checkbox');
        var allShadows = {};
        allCheckbox.each(function () {
            var el = $(this);
            var name = el.attr(nameKey);
            el.iCheck(options);
            allShadows[name] = el;
        });
        var looping = false;
        allCheckbox.on('ifToggled', function () {
            if (looping) return;

            var el = $(this);
            var my_name = el.attr(nameKey);
            var checked = el.is(':checked') ? 'check' : 'uncheck';

            looping = true;
            var self = null;
            var relatives = [];
            for (var name in allShadows) {
                if (!allShadows.hasOwnProperty(name)) continue;

                var rel = relationship(my_name, name);

                switch (rel) {
                    case 'children':
                        allShadows[name].iCheck(checked);
                        break;
                    case 'myself':
                        self = {
                            key: name,
                            el: allShadows[name],
                            rel: rel
                        };
                        break;
                    case 'brother':
                    case 'parents':
                    case 'parent':
                        relatives.push({
                            key: name,
                            el: allShadows[name],
                            rel: rel
                        });
                }
            }
            updateParents(self, relatives);
            looping = false;
        });

        function updateParents(self, parents) {
            var pt = null;
            var ps = [];
            var status = self.el.is(':checked');
            for (var i=0,len=parents.length;i<len;i++) {
                var pi = parents[i];
                var rel = relationship(self.key, pi.key);
                switch (rel) {
                    case 'brother':
                        if (status) status = pi.el.is(':checked');
                        break;
                    case 'parent':
                        pt = pi;
                        break;
                    default:
                        ps.push(pi);
                }
            }
            if (pt) {
                pt.el.iCheck(status ? 'check' : 'uncheck');
                if (ps.length) {
                    updateParents(pt, ps);
                }
            }
        }

        function relationship(cur, tar) {
            var pc = cur.split(seprator);
            var pt = tar.split(seprator);
            var par = pc.slice(0, pc.length - 1).join(seprator);
            if (pc.length === pt.length) {
                if (cur === tar) {
                    return 'myself';
                } else if (tar.startsWith(par)) {
                    return 'brother';
                } else {
                    return 'cousin';
                }
            } else if (pc.length > pt.length) {
                if (tar === par) {
                    return 'parent';
                } else {
                    return 'parents';
                }
            } else if (tar.startsWith(cur)) {
                return 'children';
            }
        }
    };
})(jQuery);