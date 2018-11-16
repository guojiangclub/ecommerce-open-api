/*去掉URL中page参数*/
function funcUrlDel(name) {
    var loca = window.location;
    var query = loca.search.substr(1);
    if (query.indexOf(name) > -1) {
        var obj = {};
        var arr = query.split("&");
        for (var i = 0; i < arr.length; i++) {
            arr[i] = arr[i].split("=");
            obj[arr[i][0]] = arr[i][1];
        }
        delete obj[name];
        return JSON.stringify(obj).replace(/[\"\{\}]/g, "").replace(/\:/g, "=").replace(/\,/g, "&");

    }
    return query;
}