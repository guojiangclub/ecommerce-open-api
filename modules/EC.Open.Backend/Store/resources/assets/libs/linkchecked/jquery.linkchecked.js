/*
 =========================================================
 jQuery LinkChecked
 Version: 0.1.1
 Timestamp: Thu, 25 Sep 2014 14:22:09 GMT+8
 =========================================================
 The MIT License (MIT)

 Copyright (c) 2014 MouLingtao

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 THE SOFTWARE.
 */
(function ($) {
    $.fn.linkchecked = function (prefix, nameKey, parentKey) {
        var that = $(this);
        $.linkchecked(prefix, nameKey, parentKey, that);
        return that;
    }
    $.linkchecked = function (prefix, nameKey, parentKey, nodeDom) {
        nameKey = nameKey ? nameKey : 'linkchecked-name';
        parentKey = parentKey ? parentKey : 'linkchecked-parent';
        nodeDom = nodeDom ? nodeDom : $(document);
        var allCheckbox = nodeDom.find('input[' + nameKey + '^=' + prefix + ']:checkbox');
        allCheckbox.each(function () {
            var that = $(this);
            if(that.attr(parentKey)) return;
            var name = that.attr(nameKey);
            var parent = name.substr(0, name.lastIndexOf('_'));
            $(this).attr(parentKey, parent);
        });
        allCheckbox.on('change', function () {
            spreadChildren($(this));
            spreadParent($(this));
        });
        var hasSpread = {};
        allCheckbox.filter(':checked').each(function () {
            var that = $(this);
            var parentName = that.attr(parentKey);
            if (!hasSpread[parentName]) {
                hasSpread[parentName] = true;
                spreadParent(that);
            }
        });
        function spreadChildren(curDom) {
            var checked = curDom.is(':checked');
            var name = curDom.attr(nameKey);
            var allChild = allCheckbox.filter('[' + parentKey + '^=' + name + ']');
            if (checked) allChild.prop('checked', 'true');
            else  allChild.removeAttr('checked');
        }

        function spreadParent(curDom) {
            var parentName = curDom.attr(parentKey);
            if (!parentName) return;
            var parentDom = allCheckbox.filter('[' + nameKey + '=' + parentName + ']:first');
            var sameLevel = allCheckbox.filter('[' + parentKey + '=' + parentName + ']');
            if (isCheckedAll(sameLevel)) {
                parentDom.prop('checked', 'true');
            } else {
                parentDom.removeAttr('checked');
            }
            spreadParent(parentDom);
        }

        function isCheckedAll(doms) {
            var isChecked = true;
            doms.each(function () {
                return isChecked = this.checked;
            });
            return isChecked;
        }
    }
})(jQuery);