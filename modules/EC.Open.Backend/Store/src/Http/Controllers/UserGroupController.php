<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/3/7
 * Time: 22:57
 */

namespace iBrand\EC\Open\Backend\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use iBrand\EC\Open\Backend\Store\Model\UserGroup;
use Illuminate\Http\Request;
use Validator;

class UserGroupController extends Controller
{

    /**会员组列表
     * @return mixed
     */
    public function grouplist(){
        $group = UserGroup::all();
      
        return view('store-backend::user_group.grouplist',compact('group'));
    }

    /**会员组新增/修改
     * @param Request $request
     * @return mixed
     */
    public function groupcreate(Request $request){
        if($request->input('id')){
            $id=$request->input('id');
            $group_edit = UserGroup::find($id);
            $action = 'edit';
        }else{
            $group_edit = new UserGroup();
            $action ='';
        }


        //$flag=settings('is_group');
        $flag=1;
        if($flag==1){
            $title='会员积分范围';
        }elseif($flag==0){
            $title='会员消费额范围';
        }
        return view('store-backend::user_group.groupstore',compact('group_edit','action','flag','title'));
    }

    /**
     * 会员组数据库新增
     * @param Request $request
     * @return mixed
     */
    public function groupstore(Request $request){
        if(request('grade')){
            $group_list=UserGroup::where('grade', request('grade'))->first();
            if(count($group_list) && $group_list->id!=request('id')){
                return response()->json([
                    'error' => '该等级值已存在,请修改!',
                    'status' => true,
                    'data' => [
                    ],
                    'error_code' => 1,
                ]);
            }else{
                $input=$request->except('id','_token');
                if($request->input('id')){

                    $group = UserGroup::find($request->input('id'));
                    $group->fill($input);
                    $group->save();

                }else{

                    $group = UserGroup::create($input);
                }
                return response()->json([
                    'error' => '',
                    'status' => true,
                    'data' => [
                        'group_id' => $group->id,
                    ],
                    'error_code' => 0,
                ]);
            }
        }else{
            return response()->json([
                'error' => '等级值不能为空!',
                'status' => true,
                'data' => [
                ],
                'error_code' => 1,
            ]);
        }



    }
   

    /**
     * 会员组删除
     * @param Request $request
     * @return mixed
     */
    public function deletedGroup(Request $request){
        UserGroup::destroy($request->input('id'));

        return redirect()->back()->withFlashSuccess('会员组已删除');
    }


}

