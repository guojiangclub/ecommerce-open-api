<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Http\Controllers;

use GuoJiangClub\EC\Open\Backend\Member\Models\User;
use iBrand\Backend\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\CommentsRepository;
use GuoJiangClub\EC\Open\Backend\Store\Repositories\GoodsRepository;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $commentsRepository;
    protected $goodsRepository;
    protected $orderRepository;

    public function __construct(CommentsRepository $commentsRepository,
                                GoodsRepository $goodsRepository)
    {
        $this->commentsRepository = $commentsRepository;
        $this->goodsRepository = $goodsRepository;
    }

    public function index(Request $request)
    {
        $view = !empty(request('status')) ? request('status') : 'show';
        $where['status'] = $view;

        $value = '';

        if (!empty(request('value'))) {
            if (request('field') == 'goods_name') {
                $value = request('value');
            } else {
                $where['point'] = request('point');
            }
        }


        $comments_list = $this->commentsRepository->getCommentsPaginated($where, $value, 15);

        $comments_list_num = count($this->commentsRepository->getCommentsPaginated($where, $value, 0));

        return LaravelAdmin::content(function (Content $content) use ($view, $comments_list, $comments_list_num) {

            $content->header('评论列表');

            $content->breadcrumb(
                ['text' => '评论管理', 'url' => 'store/comments?status=show', 'no-pjax' => 1],
                ['text' => '评论列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '评论管理']

            );

            $content->body(view('store-backend::comments.index', compact('view', 'comments_list', 'comments_list_num')));
        });
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {       
        $comment = $this->commentsRepository->find($id);
        return view('store-backend::comments.edit', compact('comment'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!empty(request()->all())) {

            $status = request('status');
            if ($status == 'hidden') {
                $recommend = 0;
            } else {
                $recommend = request('recommend');
            }

            $this->commentsRepository->update(['status' => $status, 'recommend' => $recommend], $id);
        }

        return $this->ajaxJson();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->commentsRepository->destroy($id);
        return redirect()->back()->withFlashSuccess('评论已删除');

    }

    public function create()
    {
        return LaravelAdmin::content(function (Content $content) {

            $content->header('添加评论');

            $content->breadcrumb(
                ['text' => '评论管理', 'url' => 'store/comments?status=show', 'no-pjax' => 1],
                ['text' => '添加评论', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '评论管理']

            );

            $content->body(view('store-backend::comments.create'));
        });

    }

    public function searchGoods()
    {
        $goods = $this->goodsRepository->getGoodsByName(request('name'));
        return view('store-backend::comments.includes.goods_list', compact('goods'));
    }

    public function searchUsers()
    {
        $users = User::where('mobile', 'like', '%' . request('mobile') . '%')->get();
        return view('store-backend::comments.includes.users_list', compact('users'));
    }

    public function store(Request $request)
    {
        if (!$goods_id = $request->input('goods_id')) {
            return $this->ajaxJson(false, [], 404, '请选择评论商品');
        }

        if (!$nickName = $request->input('nick_name')) {
            return $this->ajaxJson(false, [], 404, '请输入昵称');
        }

        $goods = $this->goodsRepository->find($goods_id);

        $data['item_meta'] = json_encode(['image' => $goods->img, 'spec_text' => '']);
        $data['pic_list'] = serialize($request->input('img') ? $request->input('img') : []);
        $data['contents'] = $request->input('contents');
        $data['status'] = 'show';
        $data['point'] = $request->input('point');
        $data['user_id'] = 0;
        $data['item_id'] = $goods_id;
        $this->commentsRepository->create($data);

        $goods->comment_count += 1;
        $goods->grade += $request->input('point');
        $goods->save();

        return $this->ajaxJson();
    }
}
