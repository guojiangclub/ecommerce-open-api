$(document).ready(function () {
    if ([1, 0].sort(function (a, b) {

            return a > b
        }).toString() !== '0,1') {
        Array.prototype._sort = Array.prototype.sort;
        Array.prototype.sort = function (callback) {
            if (typeof callback !== 'function') {
                return this._sort();
            } else {
                return this._sort(function () {
                    var ret = callback.apply(null, arguments);
                    if (ret === true) {
                        ret = 1;
                    } else if (ret === false) {
                        ret = -1;
                    }

                    return ret;
                })
            }
        };
    }


    // 该数据为模拟数据, 需要从服务端获取
    var app = window.skuBuilder = {};
    // app.init();

    var skuList = app.skuList = {};                 // 最终的sku列表数据, 如果有sku值则为修改, 否则为新加
    var specList = app.specList = {};               // 规格列表选择数据
    var selections = app.selections = [];           // 所有选中的规格
    var batchInputs = app.batchInputs = {};         // 批量输入的数据

    app.saveData = function () {
        if (this.submiting) return;
        this.submiting = true;

        // 整理数据
        var data = {};

        // 提交数据
    };

    app.bindEvent = function () {
        var body = $('body');
        var that = this;
        $('#sku-builder input:checkbox').on('ifToggled', function () {
            var el = $(this);
            var uuid = el.data('uuid');
            if (!uuid) return;

            var parts = uuid.split('-');
            var check = el.is(':checked');

            var ready = true;
            var colorText = el.parent().siblings('span.color').text();
            var sizeText = el.parent().siblings('span.size').text();
            var colorAdd = $(`<input type="text" data-id="${parts[1]}" data-sid="${parts[0]}" data-action="update" class="inputAdd" value="${colorText}" name="spec_alias[${parts[1]}]">`);
            var sizeAdd = $(`<input type="text" data-id="${parts[1]}" data-sid="${parts[0]}" data-action="updatesize" class="inputAdd" value="${sizeText}" name="spec_alias[${parts[1]}]">`);
            var colorSpan = el.parent().siblings('.color');
            var sizeSpan = el.parent().siblings('.size');
            for (var i = 0, l = selections.length; i < l; i++) {
                var sel = selections[i];
                if (sel.id == parts[0]) {
                    if (check) {

                        sel.count++;
                        sel.list[parts[1]] = specList[uuid];
                        colorSpan.hide().parent().after(colorAdd);
                        sizeSpan.hide().parent().after(sizeAdd);


                    } else {

                        sel.count--;
                        delete sel.list[parts[1]];
                        colorSpan.show().parent().siblings('input[type=text]').remove();
                        sizeSpan.show().parent().siblings('input[type=text]').remove();
                    }
                }
                if (sel.count === 0) ready = false;
            }


            app.updateImages();

            if (ready) {
                app.createTable();
            } else {
                app.clearTable();
            }
        });


        $('#save-sku').on('click', function () {
            app.saveData();
        });

        body.on('input propertychange', 'input[data-action=save]', function () {
            var el = $(this);
            var spec = el.parents('tr').data('spec');
            var name = el.data('name');
            var value = el.val();

            saveInput(spec, name, value);
        });

        body.on('input propertychange', 'input[data-action=batch]', function () {
            var el = $(this);
            var name = el.data('name');
            var value = el.val();
            batchInputs[name] = value;

            $('input[data-action=save][data-name=' + name + ']').each(function () {
                var $this = $(this);
                var spec = $this.parents('tr').data('spec');
                $this.val(value);
                saveInput(spec, name, value);
            });
        });

        body.on('input propertychange', 'input[data-action=update]', function () {
            var el = $(this);
            var id = el.data('id');
            var sid = el.data('sid');
            var value = el.val();


            specList[sid + '-' + id].alias = value;
            specList[sid + '-' + id].value = value;

            $('[data-action=update-' + id + ']').text(value);
        });

        body.on('input propertychange', 'input[data-action=updatesize]', function () {
            var el = $(this);

            var id = el.data('id');
            var sid = el.data('sid');
            var value = el.val();

            specList[sid + '-' + id].alias = value;
            specList[sid + '-' + id].value = value;

            $('[data-action=updatesize-' + id + ']').text(value);
        });

        body.on('click', '.colorSortable', function () {
            app.sortType = 'color';

            that.sortable(selections[0].list, 'sort');
            $('.popup').show();
        });

        body.on('click', '.sizeSortable', function () {
            app.sortType = 'size';

            if (window.skuBuilder.selections.length == 2) {
                that.sortable(selections[1].list, 'sort');
            } else if (window.skuBuilder.selections.length == 1) {
                that.sortable(selections[0].list, 'sort');
            }
            $('.popup').show();
        });

        body.on('click', '.switch', function () {
            var show = parseInt($(this).children('input').attr('value'));
            var that = $(this);
            var showObj = $(this).children('input');

            if (show == 1) {
                that.removeClass('fa-toggle-on');
                that.addClass('fa-toggle-off');
                showObj.val(0);
            } else {
                that.removeClass('fa-toggle-off');
                that.addClass('fa-toggle-on');
                showObj.val(1);
            }
            var value = showObj.val();

            var spec = that.parents('tr').data('spec');
            var name = 'is_show';
            saveInput(spec, name, value);
        });

        body.on('click', '.upload_sku_img', function () {

            var el = $(this);


            $.addImage(el, "singleHighlight", function (data) {
                var url = data[0].url;

                var id = $(this).parents('tr').find('span.color-id').data('id');

                var sid = $(this).parents('tr').data('sid');

                $(this).parents('tr').find('.block:first').html('<img width="50" src="' + url + '"><input type="hidden" name="spec_img[' + id + ']" value="' + url + '">').removeClass('hidden');

                specList[sid + '-' + id].image = url;
                var imgTag = '<img width="50" src="' + url + '"><input type="hidden" name="spec_img[' + id + ']" value="' + url + '">';

                for (var i = 0; i < selections.length; i++) {
                    var item = selections[i];
                    if (item.type == 2) {
                        for (var k in item.list) {
                            item.list[k].imgTag = imgTag
                        }
                    }
                }

            });

        });


        function saveInput(spec, name, value) {
            skuList[spec] = skuList[spec] || {};
            skuList[spec][name] = value;
        }
    };

    var img_template = $('#template-img-tr').html();
    app.updateImages = function () {
        for (var i = 0, l = selections.length; i < l; i++) {
            var sel = selections[i];
            if (sel.type == 2) {
                var tmpl = img_template;
                var html = '';
                for (var k in sel.list) {
                    if (!sel.list.hasOwnProperty(k)) continue;
                    // sel.list[k].block
                    if (sel.list[k].image) {
                        sel.list[k].class = '';
                        sel.list[k].imgTag = '<img width="50" src="' + sel.list[k].image + '"><input type="hidden" name="spec_img[' + sel.list[k].id + ']" value="' + sel.list[k].image + '">';
                    } else {
                        sel.list[k].class = ' hidden';
                    }

                    // html += utils.convertTemplate(tmpl, sel.list[k], '');
                }

                // var img_table = $('#image-table tbody');
                //
                // img_table.html(html);
                // console.log(img_table);
                break;
            }
        }
    };

    var sku_table = $('#sku-table tbody');
    var sku_template = $('#template-sku-tr').html();
    app.clearTable = function () {
        sku_table.empty();
    };

    app.createTable = function () {
        var array = [];
        var indexArray = [];

        for (var i = 0, l = selections.length; i < l; i++) {
            var sel = selections[i];
            var data = [];
            for (var k in sel.list) {
                if (!sel.list.hasOwnProperty(k)) continue;
                data.push(sel.list[k]);

            }


            var newData = [];
            data.forEach(function (item) {
                if (item.hasOwnProperty('index')) {

                    if (!!newData[item.index]) {
                        item.index = newData.length;
                        newData.push(item);
                    } else {
                        newData[item.index] = item
                    }

                } else {
                    item.index = newData.length;
                    newData.push(item);
                }
            });

            var arr = newData.filter(function (item) {
                return !!item
            });

            if (arr.length) array.push(arr);
            else array.push(data)

        }

        // console.log(selections);
        // console.log(array);
        var html = this.createRows(array);

        sku_table.html(html);
    };

    app.createRows = function (array) {

        var tmpl = sku_template;
        var html = '';
        var rows = Cartesian(array);
        // console.log(rows);

        for (var i = 0, p = rows.length; i < p; i++) {
            var rowHtml = '';
            var sku_ids = [];
            var imageCount = 0;
            var current = {};
            var sid = '';

            if (rows[i].length == 2) {
                for (var j = 0, q = rows[i].length; j < q; j++) {
                    var count = 1;
                    var item = rows[i][j];

                    if (i === 0 || rows[i - 1][j].id !== item.id) {
                        for (var k = i + 1; k < p; k++) {
                            if (rows[k][j].id === item.id) {
                                count++;
                            } else {
                                break;
                            }
                        }
                    } else {
                        count = 0;
                    }

                    if (count) {
                        var text;
                        if (item.color) {
                            imageCount = count;

                            sid = item.sid;
                            text = createColorBlock(item);
                        } else {

                            text = '<span data-action="updatesize-' + item.id + '" class="size">' + (item.alias || item.value) + '</span>';
                        }

                        // rowHtml += '<td rowspan="' + count + '">' + text + '<input type="hidden" value="'+item.index === undefined?99:item.index+'" name="_spec_index['+item.id+']" /> </td>';
                        rowHtml += `<td rowspan="${count}">${text}<input type="hidden" value="${item.index === undefined ? 99 : item.index}" name="spec_index[${item.id}]" /> </td>`;
                        // console.log(item.index);

                        if (item.image) {

                            current = item;
                        }

                    }

                    sku_ids.push(item.id);

                }
            } else {
                var count = 1;
                if (i === 0 || rows[i - 1].id !== rows.id) {
                    for (var k = i + 1; k < p; k++) {
                        if (rows[k].id === rows.id) {
                            count++;
                        } else {
                            break;
                        }
                    }
                } else {
                    count = 0;
                }

                if (count) {

                    var text;
                    // console.log(rows[i].id);
                    if (rows[i].color) {
                        imageCount = count;

                        sid = rows[i].sid;
                        text = createColorBlock(rows[i]);
                    } else {
                        text = '<span data-action="updatesize-' + rows[i].id + '" class="size">' + rows[i].value + '</span>';
                    }

                    // rowHtml += '<td rowspan="' + count + '">' + text + '<input type="hidden" value="'+item.index === undefined?99:item.index+'" name="_spec_index['+item.id+']" /> </td>';
                    // debugger
                    rowHtml += `<td rowspan="">${text}<input type="hidden" value="${rows[i].index === undefined ? 99 : rows[i].index}" name="spec_index[${rows[i].id}]" /> </td>`;

                    if (rows[i].image) {
                        current = rows[i];
                    }
                }
                sku_ids.push(rows[i].id);

            }

            // debugger

            var spec = sku_ids.sort(order).join('-');

            skuList[spec] = skuList[spec] || {};
            var data = Object.assign({is_show: 1}, batchInputs, skuList[spec]);
            data.switch_class = data.is_show == 1 ? 'fa-toggle-on' : 'fa-toggle-off';
            data.index = i;
            data.spec = spec;
            var imageText = '';
            Object.keys(current).forEach(function (item) {
                // console.log(item, current[item])
            });
            if (current.image && imageCount) {
                imageText = `<td class="image" rowspan="${imageCount}">
                        <div class="block ${current.class}">${current.imgTag}</div>
                        <button class="btn btn-primary btn-xs upload_sku_img" type="button">上传图片</button>
                        </label>
                        </td>`;

            } else if (imageCount) {
                imageText = `<td class="image" rowspan="${imageCount}">
                        <div class="block ${current.class}"></div>
                        <button class="btn btn-primary btn-xs upload_sku_img" type="button">上传图片</button>                   
                        </td>`;
            }

            rowHtml += imageText;
            //debugger
            html += '<tr data-spec="' + spec + '" data-sid="' + sid + '">' + rowHtml + utils.convertTemplate(tmpl, data, '') + '</tr>';
        }


        return html;
    };

    app.loadData = function (res, callback) {

        var specs = res.specs || [];
        var skus = res.skus || {
                skuData: [],
                specData: {}
            };

        var header = '';
        var hasImage = false;
        var listHtml = '';
        var listTmpl = $('#template-spec-list').html();
        var itemTmpl = $('#template-spec').html();



        for (var i = 0, l = specs.length; i < l; i++) {
            var sid = specs[i].id;
            var type = specs[i].type;
            var list = specs[i].list;
            var label = specs[i].label;
            var show_img = specs[i].show_img;
            if (type == 2) {
                var corlorSort = '<a href="javascript:;" class="sortable colorSortable">' + label + '排序</a>';
            } else {
                var sizeSort = '<a href="javascript:;" class="sortable sizeSortable">' + label + '排序</a>';
            }

            var selItem = {
                id: sid,
                type: type,
                list: {},
                count: 0
            };
            header += '<th class="spec_img_th_label spec_header_th_label">' + label + '</th>';

            var html = '';
            for (var k = 0, n = list.length; k < n; k++) {
                var id = list[k].id;
                var check = !!skus.specData[id];
                if (check) {
                    list[k] = Object.assign(list[k], skus.specData[id], {checked: 'checked'});

                    selItem.count++;
                    selItem.list[id] = list[k];
                }

                specList[sid + '-' + id] = list[k];


                var block = createColorBlock(list[k], true) || createNormalText(list[k]);
                var textInput = list[k].checked ? `<input type="text" data-id="${list[k].id}" data-sid="${sid}" data-action="${list[k].color ? 'update' : 'updatesize'}" class="inputAdd" value="${(list[k].alias || list[k].value)}" name="spec_alias[${list[k].id}]">` : '';
                var data = Object.assign(list[k], {sid: sid, block: block, textInput: textInput});

                html += utils.convertTemplate(itemTmpl, data, '');

            }
            // debugger
            var specItem = Object.assign(specs[i], {html: html});


            if (type == 2) {
                listHtml += corlorSort;
            } else {
                listHtml += sizeSort;
            }

            listHtml += utils.convertTemplate(listTmpl, specItem, '');
            if (type == 2) hasImage = true;
            selections.push(selItem);

            if (show_img) {
                // debugger
                $('.spec_img_th_active').addClass('spec_img_th');
            }

        }


        if (hasImage) {
            listHtml += $('#template-img-table').html();
        }

       /* var sku_header = $('#sku-table thead tr').html();
        $('#sku-table thead tr').html(header + sku_header);*/

        if ($('#sku-table thead .spec_header_th_label').hasClass('spec_img_th_label')) {
            var child = document.getElementsByClassName('spec_img_th_label');
            console.log(child,'child');
            for (var i = 0, j=child.length; i<j; i++) {
                console.log(child[i]);
                // debugger
                child[0].remove(child[0]);
            }
            // debugger
            var sku_header = $('#sku-table thead tr').html();

            $('#sku-table thead tr').html(header + sku_header);
        } else {
            console.log(1);
            var sku_header = $('#sku-table thead tr').html();
            $('#sku-table thead tr').html(header + sku_header);
        }

        $('#module-specs').html(listHtml);

        for (var j = 0, m = skus.skuData.length; j < m; j++) {
            var spec = skus.skuData[j].specID.sort(order).join('-');
            skuList[spec] = skus.skuData[j];
        }


        if (callback && typeof callback === 'function') callback();
    };

    app.init = function (data) {

        this.skuList = skuList = {};                 // 最终的sku列表数据, 如果有sku值则为修改, 否则为新加
        this.specList = specList = {};               // 规格列表选择数据
        this.selections = selections = [];           // 所有选中的规格
        this.batchInputs = batchInputs = {};         // 批量输入的数据

        var that = this;
        this.loadData(data, function () {
            // $.iCheck({
            //     checkboxClass: 'icheckbox_flat-green',
            //     prefix: 'spec'
            // });


            /*$('#module-specs').find("input").iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });*/
            $('.module-specs-create').find("input").iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%'
            });

            var ready = true;
            for (var i = 0, l = selections.length; i < l; i++) {
                if (selections[i].count === 0) {
                    ready = false;
                    break;
                }
            }

            if (ready) {
                that.updateImages();
                that.createTable();
            }

            that.bindEvent();
        });

    };


    app.sortable = function (items, id) {
        var sorEl = document.getElementById(id);
        //console.log(sorEl);
        var html = '';
        var sortArr = [];

        Object.keys(items).forEach(function (key, index) {
            if (items[key].hasOwnProperty('index')) {

                if (!!sortArr[items[key].index]) {

                    items[key].index = Object.keys(items).length;
                    sortArr.push(items[key])

                } else {

                    sortArr[items[key].index] = items[key]
                }

            } else {

                items[key].index = Object.keys(items).length;
                sortArr.push(items[key])

            }
            // html+=`<li data-id="${items[key].id}" >${items[key].value}</li>`;
        });

        // console.log(sortArr)
        sortArr.forEach(function (item) {
            html += `<li data-id="${item.id}" >${item.alias || item.value}</li>`;
        });

        $('#' + id).html(html);

        var sortable = new Sortable(
            sorEl, {animation: 500}
            );
        // debugger

        var body = $('body');

        body.on('click', '.sortCancle', function () {

            $('.popup').hide()
        });

        body.on('click', '.sortOK', function () {

            var arr = [];
            var newObj = {};
            $('.sortMain ul li').each(function (index, item) {
                $(this).data('id') && arr.push($(this).data('id'))
            });

            var selectionList;

            if (app.sortType == 'color') selectionList = selections[0].list;

            if (app.sortType == 'size') {
                if (window.skuBuilder.selections.length == 2) {
                    selectionList = selections[1].list;
                } else if (window.skuBuilder.selections.length == 1) {
                    selectionList = selections[0].list;
                }
            }

            var selectsId = Object.keys(selectionList);

            arr.forEach(function (item, index) {
                if (selectsId.indexOf(item)) {
                    selectionList[item].index = index;
                    newObj[item] = selectionList[item]
                }
            });

            if (app.sortType == 'size') {
                if (window.skuBuilder.selections.length == 2) {
                    selections[1].list = newObj;
                } else if (window.skuBuilder.selections.length == 1) {
                    selections[0].list = newObj;
                }
            }
            if (app.sortType == 'color')        selections[0].list = newObj;

            $('.popup').hide();

            // if (  )
            if (window.skuBuilder.selections.length == 2){
                if (selections[0].count && selections[1].count) {
                    app.updateImages();
                    app.createTable();
                }
            } else {
                if (selections[0].count) {
                    app.updateImages();
                    app.createTable();
                }
            }


        })
    };

    // 创始色块
    function createColorBlock(item, unlink) {
        var display = item.checked ? 'none' : 'inline';
        if (item.color && item.id && item.value) {
            if (unlink) {
                return '<span class="color-block" style="background-color: #' + item.color.replace('#', '') + '"></span>' +
                    '<span data-action="update-' + item.id + '" class="color"  style="display: ' + display + '">' + (item.alias || item.value) + '</span>';
            }
            return '<span class="color-block" style="background-color: #' + item.color.replace('#', '') + '"></span>' +
                '<span data-action="update-' + item.id + '" >' + (item.alias || item.value) + '</span>' +
                '<span class="color-id" data-id="' + item.id + '"></span> ';
        } else {
            return ''
        }
    }

    function createNormalText(item) {
        var display = item.checked ? 'none' : 'inline';
        if (item.id && item.value) {
            return '<span data-action="updatesize-' + item.id + '" class="size" data-id="' + item.id + '" style="display: ' + display + '">' + (item.alias || item.value) + '</span>';
        } else {
            return '';
        }
    }

    // 追加并创建新数组
    function combine(a, b) {
        if (!(a instanceof Array)) {
            a = [a];
        }
        return a.concat(b);
    }

    //笛卡尔积
    function cartesian(a, b) {

        var ret = [];
        for (var i = 0; i < a.length; i++) {
            for (var j = 0; j < b.length; j++) {
                ret.push(combine(a[i], b[j]));
            }
        }
        return ret;
    }


    //多个一起做笛卡尔积
    function Cartesian(data) {

        var len = data.length;
        switch (len) {
            case 0:
                return [];
            case 1:
                return data[0];
            default:
                var r = data[0];
                for (var i = 1; i < len; i++) {
                    r = cartesian(r, data[i]);
                }
                return r;
        }
    }

    //比较数字大小
    function order(a, b) {
        return Number(a) > Number(b);
    }


    /*var specs = {
        "specs": [ {
            "id": 4,
            "label": "嗒嗒",
            "type": 1,
            "show_img": true,
            "list": [{
                "id": 324,
                "value": "39"
            }, {
                "id": 325,
                "value": "39.5"
            }, {
                "id": 328,
                "value": "41"
            }, {
                "id": 332,
                "value": "44"
            }]
        },{
            "id": 2,
            "label": "香蕉",
            "type": 2,
            "show_img": true,
            "list": [{
                "id": 1,
                "value": "黑色",
                "color": "000000"
            }, {
                "id": 2,
                "value": "灰色",
                "color": "6E6E6E"
            }, {
                "id": 3,
                "value": "白色",
                "color": "FFFFFF"
            }, {
                "id": 4,
                "value": "米黄色",
                "color": "FFF3A6"
            }, {
                "id": 5,
                "value": "深灰色",
                "color": "454545"
            }, {
                "id": 6,
                "value": "卡其色",
                "color": "B0853C"
            }]
        }],
        "skus": {
            "skuData": [],
            "specData": []
        }
    }
    window.skuBuilder.init(specs);*/

});
