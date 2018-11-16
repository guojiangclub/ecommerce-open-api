<script>
    //   上传图片对象
    function UploadImage(obj) {
        var that = this;
        // 上传图片的索引号
        this.index = obj.id;
        this.files = obj.files;
        this.dom = $('<li style="display: inline-block"></li>').addClass('num-' + that.index);
        this.init();
    }
    UploadImage.prototype.init = function () {
        this.selectDelete();
    };
    // 选中删除
    UploadImage.prototype.selectDelete = function () {
        var that = this;
        this.dom.on("click", function () {
            var i = imgList.uploadImageList.findIndex(function (val) {
                return val.index === that.index
            });
            imgList.uploadImageList.splice(i, 1);
            // $(".upload_result").remove(that.dom);
            that.dom.remove();
        });
    };
    var imgContainer = (function () {
        var result = "";

        function ImageList() {
            this.selectNum = 0;  //选中图片数量
            this.selectImageList = [];  //选中图片列表
            this.uploadImageList = [];  //上传的图片选中列表
        };
        ImageList.prototype.bindList = function (obj) {
            $("#imgContainer").html("");
            if (!obj) return;
            for (var k in obj) {
                var listObj = new List(obj[k]).dom;
                $("#imgContainer").append(listObj);
            }
        };
        ImageList.prototype.bindDom = function (method, obj) {
            // 图片容器清空
            this.bindList(obj.column);
            // this.allNum=obj.number;  //图片容器总数
            for (var k in obj.list) {
                // 遍历选中图片列表判断图片是否在选中状态
                var flag = false;
                var imgObj = new Image(method, obj.list[k]).dom;
                for (var i in this.selectImageList) {
                    if (obj.list[k].id == this.selectImageList[i].index) flag = true;
                }
                if (flag) {
                    imgObj = imgObj.addClass("selected");
                }
                $("#imgContainer").append(imgObj);
            }
            // selectImage=$("#addImage").html();//缓存
        };
        ImageList.prototype.bindSelectNum = function () {
            var str = "";
            if (this.selectNum == 0) {
                $("#imgContainerInfo").html("");
                return;
            }
            str = "<p>已选择" + this.selectNum + "张图片</p>";
            $("#imgContainerInfo").html(str);
        };
        return function () {
            if (!result) {
                result = new ImageList();
                return result;
            }
            return result;
        }
    })();
    UploadImage.prototype.bindDom = function (res) {
        this.dom.append('<img src="' + res + '"  />');
        return this;
    };

    var imgList = imgContainer();
    $("#upload_img").on("change",function(){
        var file = document.getElementById("upload_img").files;
        //将文件以Data URL形式读入页面
        for (var k = 0; k < file.length; k++) {
            var reader = new FileReader();
            reader.readAsDataURL(file[k]);
            reader.onload = (function (i) {
                var obj = {};
                var id = Math.random() * (Date.now());
                obj.id = id;
                obj.files = file[k];
                var imgObj = new UploadImage(obj);
                return function () {
                    var that = this;
                    $(".upload_result").append(imgObj.bindDom(that.result).dom);
                    imgList.uploadImageList.push(imgObj);
                }
            })(k)
        }
        console.log(imgList.uploadImageList);
    });


    $("#confirm_upload").on("click", function () {
        // 数据适配
        if (imgList.uploadImageList.length == 0) {
            return;
        }
        var dataList = [];
        for (var k in imgList.uploadImageList) {
            dataList.push(imgList.uploadImageList[k].files);
        }
        // console.log(dataList);
        // 发送给后台
        var fd = new FormData();
        for (var i in dataList) {
            fd.append("upload_image[]", dataList[i]);
        }

        fd.append("category_id", $("input[name='category_id']").val());

        $.ajax({
            url: "{{route('admin.image.postUpload')}}",
            type: "POST",
            data: fd,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status) {
                    swal({
                        title: "上传成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        $('#category_modal').hide();
                        location.reload();
                    });
                } else {
                    swal(data.message, "", "error");
                }

            }
        });
    });


</script>