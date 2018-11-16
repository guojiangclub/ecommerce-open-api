@extends('store-backend::layouts.bootstrap_modal')

@section('modal_class')
    modal-lg
@stop
@section('title')
    选择会员
@stop

@section('after-styles-end')
    {!! Html::style(env("APP_URL").'/assets/backend/libs/ladda/ladda-themeless.min.css') !!}
@stop


@section('body')
    <div class="app">
        <div class="row">
            <input type="hidden" value="{{$id}}" id="role_id" >
            <div class="form-group col-md-12">
                <label class="col-sm-2 control-label">会员名</label>
                <div class="col-sm-6"><input type="text" name="name" value=""  v-model="name" class="form-control"></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-sm-2 control-label">邮箱</label>
                <div class="col-sm-6"><input type="text" name="email" value=""  v-model="email"   class="form-control"></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-sm-2 control-label">电话</label>
                <div class="col-sm-6"><input type="text" name="mobile" value="" v-model="mobile"   class="form-control"></div>
                <button class="ladda-button btn btn-primary" id="send" @click="Search()" >查找</button>
            </div>

            <div class="clearfix"></div>
            <div class="hr-line-dashed "></div>

            <div class="panel-body">
                <h3 class="header">请选择会员：</h3>
                <div class="table-responsive" id="usersList">
                    <table class="table table-hover table-striped" v-if="data.length" >
                        <tbody>
                        <!--tr-th start-->
                        <tr>
                            <th>ID</th>
                            <th>会员名</th>
                            <th>邮箱</th>
                            <th>电话</th>
                        </tr>
                        <!--tr-th end-->
                        <tr  v-for="(item,index) in data">
                            <td>
                                <el-checkbox-group v-model="checked">
                                    <el-checkbox  :label="item.id" :id="'checked'+item.id"></el-checkbox>
                                </el-checkbox-group>
                            </td>
                            <td>{#item.name#}</td>
                            <td>{#item.email#}</td>
                            <td>{#item.mobile#}</td>

                        </tr>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="block" style="width:300px; margin: 0 auto;padding: 10px 0;" v-if="data.length">
                    <span class="demonstration"></span>
                    <el-pagination
                    @current-change="handleCurrentChange"
                    layout="prev, pager, next"
                    :total="total"
                    :current-page="currentPage"
                    :page-size="pageSize"
                    >
                    </el-pagination>
                </div>

            </div>
        </div>

        <button style="position: absolute;bottom:-50px;right:20px"   class="ladda-button btn btn-primary pull-right"  id="send" @click="ConfirmBtn()">确定</button>
    </div>
@stop


@section('footer')
    <!-- 先引入 Vue -->
    <script>
        var UsersSearch='{{route('admin.RoleManagement.role.UsersSearchList')}}';
        var allotRole='{{route('admin.RoleManagement.role.allotAddRole')}}';
        var roleIndex='{{route('admin.RoleManagement.role.index')}}';
    </script>
    <button type="button" class="btn btn-link" data-dismiss="modal" style="margin-right: 80px;">取消</button>
    {{--<button type="button"  class="ladda-button btn btn-primary" > 确定</button>--}}
    @include('member-backend::role.includes.script')

@stop








