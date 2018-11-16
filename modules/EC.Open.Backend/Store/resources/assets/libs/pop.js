/**
 * Created by admin on 2017/3/1.
 */
// Array的findIndex方法
(function () {
	if (!Array.prototype.findIndex) {
		Object.defineProperty(Array.prototype, 'findIndex', {
			value: function (predicate) {
				if (this == null) {
					throw new TypeError('"this" is null or not defined');
				}
				var o = Object(this);
				var len = o.length >>> 0;
				if (typeof predicate !== 'function') {
					throw new TypeError('predicate must be a function');
				}
				var thisArg = arguments[1];
				var k = 0;
				while (k < len) {
					var kValue = o[k];
					if (predicate.call(thisArg, kValue, k, o)) {
						return k;
					}
					k++;
				}
				return -1;
			}
		});
	}
})();
(function ($) {
	
	var insertImgList = [];
	
	// 模态框dom字符串
	var str = "";
	str += '<div class="modal fade" id="addImage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
	str += '</div>';
	// dom节点添加到body中
	$("body").append(str);
	// 选择图片字符串
	var selectImage = '   <div class="modal-dialog modal-lg selectImage">';
	selectImage += '      <div class="modal-content">';
	selectImage += '          <div class="modal-header">';
	selectImage += '              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	selectImage += '               <h5 class="modal-title" class="pull-left" id="myModalLabel">我的图片</h5>';
	selectImage += '           </div>';
	selectImage += '          <div class="modal-body clearfix" >';
	selectImage += '            <div class="nav pull-left col-sm-3">';
	selectImage += '              <div class="treeview" id="el-image-treeview">';
	selectImage += '              </div>';
	selectImage += '            </div>';
	selectImage += '            <div class="nav_content col-sm-9">';
	selectImage += '               <ul class="clearfix" id="el-image-imgContainer">';
	selectImage += '               </ul>';
	selectImage += '               <div class="nav_footer">';
	selectImage += '                 <div class="" id="el-image-pages"></div>';
	selectImage += '               </div>';
	selectImage += '            </div>';
	selectImage += '          </div>';
	selectImage += '          <div class="modal-footer">';
	selectImage += '             <button type="button" id="el-image-confirm" class="btn btn-primary">确认</button>';
	selectImage += '             <div class="img_comntainer_info" id="el-image-imgContainerInfo"></div>';
	selectImage += '         </div>';
	selectImage += '     </div>';
	selectImage += '   </div>';
	// 上传图片模块、
	var uploadImage = '   <div class="modal-dialog modal-lg uploadImage">';
	uploadImage += '      <div class="modal-content">';
	uploadImage += '          <div class="modal-header">';
	uploadImage += '              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
	uploadImage += '               <h5 class="modal-title" class="pull-left" id="myModalLabel"><span id="el-image-myPicture">< 我的图片</span> | 上传图片</h5>';
	uploadImage += '           </div>';
	uploadImage += '          <div class="modal-body clearfix" >';
	uploadImage += '             <hr>';
	uploadImage += '             <div class="netImg form-horizontal">';
	uploadImage += '                <div class=form-group>';
	uploadImage += '                   <label for="localImg" class="col-sm-2 control-label">本地图片 :</label>';
	uploadImage += '                   <div class="col-sm-10 row">';
	uploadImage += '                      <div class="img_container">';
	uploadImage += '                        <div class="upload_result">';
	uploadImage += '                        </div>';
	uploadImage += '                        <div class="btn_upload_container">';
	uploadImage += '                            +';
	uploadImage += '                        </div>';
	uploadImage += '                      </div>';
	uploadImage += '                   </div>'
	uploadImage += '                </div>';
	uploadImage += '             </div>';
	uploadImage += '          </div>';
	uploadImage += '          <div class="modal-footer">';
	uploadImage += '             <button type="button" id="el_image_confirm_upload" class="btn btn-primary">确认</button>';
	uploadImage += '         </div>';
	uploadImage += '     </div>';
	uploadImage += '   </div>';
	// 面向对象思想
	//   完整图片对象
	function Image(method, obj) {
		var that = this;
		this.index = obj.id;//图片的id
		this.path = obj.url;//图片本地url
		this.imgName = obj.name;//图片名字
		this.url = obj.remote_url; //图片远程URL
		this.dom = $('<li class="el-image-modal-li col-sm-3"></li>').addClass('num-' + that.index);
		this.init(method);//图像列表初始化
	}
	
	Image.prototype.init = function (method) {
		this.bindDom();
		this[method]();
		// this.selectHighlight();
		return this;
	};
	// 绑定dom元素
	Image.prototype.bindDom = function () {
		var str = "";
		str += "<img src=" + this.path + " alt=''>";
		str += "<p>" + this.imgName + "</p>";
		str += "<span class='sel'><i></i></span>";
		this.dom.html(str);
		return this;
	};
	// 图片数据适配
	Image.prototype.adapter = function () {
		var obj = {};
		for (var k in this) {
			if (k == "index" || k == "path" || k == "imgName" || k == "url") {
				obj[k] = this[k];
			}
		}
		return obj;
	};
	// 多选高亮
	Image.prototype.selectHighlight = function () {
		var that = this;
		that.dom.on("click", function () {
			if (that.dom.hasClass("selected")) {
				imgList.selectNum--;
				that.dom.removeClass("selected");
				var i = imgList.selectImageList.findIndex(function (val) {
					return val.index === that.index
				});
				imgList.selectImageList.splice(i, 1);
				// 数量同步
				imgList.bindSelectNum();
			}
			else {
				that.dom.addClass("selected");
				// 添加到提交list中
				imgList.selectNum++;
				imgList.selectImageList.push(that.adapter());
				// 数量同步
				imgList.bindSelectNum();
			}
		});
		return this;
	};
	// 单选高亮
	Image.prototype.singleHighlight = function () {
		var that = this;
		that.dom.on("click", function () {
			if (that.dom.hasClass("selected")) {
				that.dom.removeClass("selected");
				imgList.selectImageList = [];
			}
			else {
				that.dom.addClass("selected").siblings().removeClass("selected");
				imgList.selectImageList = [];
				imgList.selectImageList.push(that.adapter());
			}
		});
	};
	
	// 目录对象
	function List(obj) {
		if (!obj) return;
		var that = this;
		this.content = obj.name;
		//this.url="";
		this.dom = $('<li  class="column el-image-modal-li col-sm-3"></li>').addClass('list-' + obj.id);
		this.id = obj.id;
		this.init();
	}
	
	List.prototype.init = function () {
		this.bindDom();
		this.dbclick();
		return this;
	};
	List.prototype.bindDom = function () {
		var str = "";
		str += "<span>" + this.content + "</span>";
		this.dom.html(str);
		return this;
	};
	// 双击效果
	List.prototype.dbclick = function () {
		var that = this;
		this.dom.on("click", function () {
			getData(AppUrl + "/admin/store/image/file/modal/get_image?category_id=" + that.id);
		});
	};
	
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
	UploadImage.prototype.bindDom = function (res) {
		this.dom.append('<img src="' + res + '"  />');
		return this;
	};
	UploadImage.selectHighlight = function () {
		$(".btn_upload_container").append("<input class='btn_upload' accept='image/*' id='upload_img' multiple type='file'>");
		// 事件解绑
		$("#addImage").off("change", "#upload_img");
		// 事件委托
		$("#addImage").on("change", "#upload_img", function () {
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
		})
	};
	UploadImage.singleHighlight = function () {
		$(".btn_upload_container").append("<input class='btn_upload' id='upload_img' type='file'>");
		// 事件解绑
		$("#addImage").off("change", "#upload_img");
		// 事件委托
		$("#addImage").on("change", "#upload_img", function () {
			var file = document.getElementById("upload_img").files;
			console.log(file);
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
						// var imgObj=new UploadImage(mthod,obj);
						$(".upload_result").html(imgObj.bindDom(that.result).dom);
						imgList.uploadImageList = [];
						imgList.uploadImageList.push(imgObj);
					}
				})(k)
			}
			setTimeout(function () {
				console.log(imgList.uploadImageList);
			}, 1000)
			
		});
	};
	// 图片容器对象  单例模式
	var imgContainer = (function () {
		var result = "";
		
		function ImageList() {
			this.selectNum = 0;  //选中图片数量
			this.selectImageList = [];  //选中图片列表
			this.uploadImageList = [];  //上传的图片选中列表
			this.categoryId = ""; //id
		}
		
		ImageList.prototype.bindList = function (obj) {
			$("#el-image-imgContainer").html("");
			if (!obj) return;
			for (var k in obj) {
				var listObj = new List(obj[k]).dom;
				$("#el-image-imgContainer").append(listObj);
			}
		};
		
		ImageList.prototype.bindDom = function (method, obj) {
			// 图片容器清空
			this.bindList(obj.sub);
			// this.allNum=obj.number;  //图片容器总数
			
			obj.list = obj.data;
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
				
				$("#el-image-imgContainer").append(imgObj);
			}
		};
		
		ImageList.prototype.bindSelectNum = function () {
			var str = "";
			if (this.selectNum == 0) {
				$("#el-image-imgContainerInfo").html("");
				return;
			}
			str = "<p>已选择" + this.selectNum + "张图片</p>";
			$("#el-image-imgContainerInfo").html(str);
		};
		
		return function () {
			if (!result) {
				result = new ImageList();
				return result;
			}
			return result;
		}
	})();
	var imgList = imgContainer();
	// 定义一个方法和函数缓存变量
	var mthod = "";
	var f = "";
	var doms = ""
	
	//数据获取模块
	function getData(url) {
		$('#el-image-pages').html('');
		$('#el-image-pages').pages({
			url: url,
			get: $.get,
			autoUrl: false,
			marks: {
				total: 'data.last_page',
				index: 'data.current_page',
				data: 'data'
			},
			count: 4,
			texts: {
				prev: "上一页",
				next: "下一页"
			}
		}, function (data) {
			imgList.bindDom(mthod, data);
		});
	}
	
	// 给$对象添加方法
	$.addImages = function (dom, method, fn) {
		// 缓存方法和函数
		mthod = method;
		f = fn;
		doms = dom;
		// 填充dom字符串
		$("#addImage").html(selectImage);
		// 打开模态框
		$("#addImage").modal();
		// 更新数量
		imgList.bindSelectNum();
		
		$.http.get(AppUrl + '/admin/store/image/file/modal/get_category', function (result) {
			// 请求成功且返回status === true时的回调
			
			if (!result.data) return;
			for (var k in result.data) {
				if (result.data[k].state) imgList.categoryId = result.data[k].id;
				break;
			}
			$('#el-image-treeview').treeview({
				data: result.data,
				showTags: true,
				collapseAll: {
					silent: true
				}
			});
			
			// 节点点击事件
			$("#el-image-treeview").on("nodeSelected", function (e, data) {
				var id = data.id;
				imgList.categoryId = id;
				getData(AppUrl + "/admin/store/image/file/modal/get_image?category_id=" + id);
			});
		});
		
		//默认获取图片数据
		getData(AppUrl + "/admin/store/image/file/modal/get_image");
		
		
		// 确认框绑定事件
		$("#addImage").on("click", "#el-image-confirm", function () {
			if (imgList.selectImageList.length === 0) {
				return;
			}
			
			for (var i = 0; i < imgList.selectImageList.length; i++) {
				insertImgList.push({src: imgList.selectImageList[i].path});
			}
			
			var data_type = '';
			$('.editor_li').each(function () {
				if ($(this).hasClass('active')) {
					console.log($(this).children('a').attr('data-type'));
					data_type = $(this).children('a').attr('data-type');
				}
			});
			
			if (data_type == 'ue') {
				ue.execCommand('insertimage', insertImgList);
			} else if (data_type == 'uepc') {
				uepc.execCommand('insertimage', insertImgList);
			} else if (data_type == 'ue_collocation') {
				ue_collocation.execCommand('insertimage', insertImgList);
			}
			
			
			imgList.selectImageList = [];
			imgList.selectNum = 0;  //选中图片数量
			insertImgList = [];
			// 关闭模态框
			$('#addImage').modal('hide');
		});
	};
	
	// 模态框关闭事件
	$("#addImage").on("hidden.bs.modal", function () {
		// 清空所选的列表
		imgList.selectImageList = [];
		imgList.selectNum = 0;
		imgList.uploadImageList = [];
	});
})(jQuery);