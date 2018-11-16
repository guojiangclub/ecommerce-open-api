<script>
    new Vue({
        delimiters: ['{#', '#}'],
        el:'.app',
        data:{
            role_id:'',
            name:'',
            email:'',
            mobile:'',
            data:[],
            checked:[],
            pageSize:20,
            total:1,
            currentPage:1,
            oldChecked:[],
            uid:[]
        },
        methods:{
            handleCurrentChange:function(val) {
                this.currentPage = val;
                var that = this;
                that.data=[];
                $.ajax({
                    type:"get",
                    url:UsersSearch,
                    data:{'pageSize':that.pageSize,'page':val,'name':that.name,'email':that.email,'mobile':that.mobile},
                    success:function(res){
                        if(res.status){
                            that.data=res.data.data;
                            that.total=res.data.total;
                            that.pageSize=parseInt(res.data.per_page);
                            that.currentPage=res.data.current_page;
                            that.Disabled();

                        }
                    }
                });
            },

            Search:function () {
                var that = this;
                that.data=[];
                var role_id=$('#role_id').val();
                that.role_id=role_id;
                $.ajax({
                    type:"get",
                    url:UsersSearch,
                    data:{'pageSize':that.pageSize,'name':that.name,'email':that.email,'mobile':that.mobile,'role_id':that.role_id},
                    success:function(res){
                        if(res.status){
                            that.data=res.data.data;
                            that.total=res.data.total;
                            that.pageSize=parseInt(res.data.per_page);
                            that.currentPage=res.data.current_page;
                            that.Disabled();
                        }
                    }
                });
            },

            ConfirmBtn:function () {
                var role_id=$('#role_id').val();
                var that = this;
                that.role_id=role_id;
                var token=$('meta[name="_token"]').attr('content');
                var user_id=[];
                for (var i = 0; i<= that.checked.length; i++) {
                    var uid=that.checked[i];
                    if($.inArray(uid,that.oldChecked)==-1){
                        if(typeof(uid) !== "undefined"){
                            console.log(uid);
                            user_id.push(uid);
                        }
                    };
                }
//                if(user_id.length<=0){
//                        swal({
//                            title: "请勾选会员",
//                            text: "",
//                            type: "error"
//                        });
//                    }

                $.ajax({
                    type:"post",
                    url:allotRole,
                    data:{'_token':token,'uid':user_id,'rid':that.role_id},
                    success:function(res){
                        if(res.status){
                            swal({
                                title: "保存成功",
                                text: "",
                                type: "success"
                            },function () {
                                window.location.reload();
                            });
                        }
                    }
                });

            },

            getData:function () {
                var that = this;
                that.data=[];
                var role_id=$('#role_id').val();
                that.role_id=role_id;
                $.ajax({
                    type:"get",
                    url:UsersSearch,
                    data:{'pageSize':that.pageSize,'role_id':that.role_id},
                    success:function(res){
                        if(res.status){
                            that.data=res.data.data;
                            that.total=res.data.total;
                            that.pageSize=parseInt(res.data.per_page);
                            that.currentPage=res.data.current_page;
                            that.checked=res.checked;
                            that.oldChecked=res.checked;
                            that.Disabled();
                        }
                    }
                });
            },
            one:function(){
                console.log('ok');
            },
            Disabled: function () {
                var that = this;
                that.$nextTick(function () {
                    for (var i = 0; i<= that.oldChecked.length; i++) {
                        var el = $('#checked'+ that.oldChecked[i]).find('.el-checkbox__input').hasClass('is-checked');
                        if (el) {
                            $('#checked'+ that.oldChecked[i]).find('.el-checkbox__input').addClass('is-disabled');
                            $('#checked'+ that.oldChecked[i]).find('.el-checkbox__original').attr("disabled",true);
                        }
                    }
                })
            }

        },
        mounted(){
            this.getData();
        }

    })
</script>