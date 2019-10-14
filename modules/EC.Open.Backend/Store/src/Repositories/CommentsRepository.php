<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Store\Model\OrderComment;

/**
 * Class CommentsRepositoryEloquent
 * @package namespace App\Repositories;
 */
class CommentsRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return OrderComment::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }


    /**
     * 根据商品ID获取评论数据
     * @param $id
     * @return mixed
     */
    public function getCommentByGoodsID($id, $skip, $limit)
    {
        return $this->scopeQuery(function ($query) use ($id, $skip, $limit) {
            $query = $query->where(['goods_id' => $id, 'audit' => 1]);
            $query = $query->skip($skip);
            $query = $query->limit($limit);

            return $query->orderBy('created_at', 'desc');
        })->all();
    }

    /**
     * 获取推荐评论
     * @param $goodsID
     */
    public function getRecommendByGoodsId($goodsID)
    {
        return $this->findWhere(['goods_id' => $goodsID, 'recommend' => 1])->first();
    }


    /**获取评论分页列表
     * @param array $goods_id
     * @param int $where
     * @param int $limit
     * @return mixed
     */

    public function getCommentsPaginated($where, $value, $limit = 50)
    {

        $comments = $this->scopeQuery(function ($query) use ($where) {
            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            return $query->orderBy('updated_at', 'desc');
        })->with('goods')->with('user')
            ->whereHas('goods', function ($query) use ($value) {
                if ($value) {
                    $query->where('name', 'like', '%' . $value . '%');
                }
            });


        if ($limit == 0) {
            return $comments->all();
        } else {
            return $comments->paginate($limit);
        }

    }


    public function destroy($id)
    {
        $comments = $this->find($id);
        if ($comments->delete())
            return true;
        throw new GeneralException("删除评论失败，请重试!");

    }


}
