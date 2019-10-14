<?php

namespace GuoJiangClub\EC\Open\Backend\Member\Repository;

use GuoJiangClub\Component\Point\Repository\PointRepository;
use GuoJiangClub\EC\Open\Backend\Store\Exceptions\GeneralException;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use GuoJiangClub\EC\Open\Backend\Member\Models\User;

/**
 * Class UserRepositoryEloquent
 * @package namespace App\Repositories\Backend;
 */
class UserRepository extends BaseRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }


    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * @param $id
     * @param bool $withRoles
     * @return mixed
     * @throws GeneralException
     */
    public function findOrThrowException($id, $withRoles = false)
    {
        if ($withRoles)
            $user = User::with('roles')->find($id);
        else
            $user = User::with('bind')->find($id);

        if (!is_null($user)) return $user;

        throw new GeneralException('That user does not exist.');
    }


    /**
     * @param $where
     * @param int $limit
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */

    public function searchUserPaginated($where, $limit = 50, $order_by = 'id', $sort = 'desc')
    {
        $data = $this->scopeQuery(function ($query) use ($where) {
            if (is_array($where)) {
                foreach ($where as $key => $value) {
                    if ($key == 'integral') {
                        $query = $query->whereBetween($key, $value);
                    } elseif (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }
            return $query->orderBy('created_at', 'desc');
        });
        if ($limit == 0) {
            return $data->all();
        }
        return $data->paginate($limit);

    }


    /**
     * @param $id
     * @param $status
     * @return bool
     * @throws GeneralException
     */
    public function mark($id, $status)
    {
        if (auth()->id() == $id && ($status == 0 || $status == 2))
            throw new GeneralException("不能对自己进行此操作");

        $user = $this->findOrThrowException($id);
        $user->status = $status;

        if ($user->save())
            return true;

        throw new GeneralException("操作遇到问题，请重试！");
    }

    /**
     * @param $where
     * @param int $limit
     * @param string $order_by
     * @param string $sort
     * @return mixed
     */

    public function getDeletedUsersPaginated($where, $limit = 50, $order_by = 'id', $sort = 'desc')
    {

        return $this->scopeQuery(function ($query) use ($where) {
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
            return $query->with('roles')->orderBy('updated_at', 'desc')->onlyTrashed();
        })->paginate($limit);
    }


    /**
     * @param $id
     * @param $input
     * @return bool
     * @throws GeneralException
     */
    public function updatePassword($id, $input)
    {
        $user = $this->findOrThrowException($id);

        $user->password = $input['password'];
        if ($user->save())
            return true;

        throw new GeneralException('修改密码失败，请重试！');
    }


    /**
     * 获取用户导出分页数据
     * @param $where
     * @param $time
     * @return array
     */
    public function getExportUserData($where, $time, $limit = 50)
    {
        $data = [];
        $user_list = $this->scopeQuery(function ($query) use ($where, $time) {
            if (is_array($where) && count($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);

                    } else {
                        $query = $query->where($key, $value);
                    }

                }
            }
            if (is_array($time)) {
                foreach ($time as $key => $value) {
                    if (is_array($value) && count($time)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }
            return $query->orderBy('updated_at', 'desc');
        })->with('bind')->paginate($limit);

        $lastPage = $user_list->lastPage();

        $i = 0;
        foreach ($user_list as $item) {
            if (isset($item->bind)) {
                $item->open_id = $item->bind->open_id;
            }

            $i++;
            $data[$i][] = $this->filterName($item->nick_name);
            $data[$i][] = isset($item->email) ? $item->email : '';
            $data[$i][] = isset($item->mobile) ? $item->mobile : '';
            $data[$i][] = app(PointRepository::class)->getSumPointValid($item->id);
            $data[$i][] = $item->created_at->toDateTimeString();
        }


        return [
            'users' => $data,
            'lastPage' => $lastPage
        ];

    }

    protected function filterName($name)
    {
        $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        if (preg_match($regex, $name) OR str_contains($name, 'base64')) {
            return '';
        }
        return $name;
    }
}
