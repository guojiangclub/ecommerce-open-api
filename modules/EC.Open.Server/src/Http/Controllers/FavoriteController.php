<?php

/*
 * This file is part of ibrand/EC-Open-Server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Server\Http\Controllers;

use GuoJiangClub\Component\Favorite\RepositoryContract as FavoriteRepository;
use GuoJiangClub\EC\Open\Server\Transformers\FavoriteTransformer;

class FavoriteController extends Controller
{
    protected $favoriteRepository;

    public function __construct(FavoriteRepository $favoriteRepository)
    {
        $this->favoriteRepository = $favoriteRepository;
    }

    public function index()
    {
        $limit = request('limit') ?: 15;

        $type = request('type') ?: 'goods';

        $fav = $this->favoriteRepository->getByUserAndType(request()->user()->id, $type, $limit);

        return $this->response()->paginator($fav, new FavoriteTransformer());
    }

    public function store()
    {
        $id = request('favoriteable_id');
        $type = request('favoriteable_type');
        $favorite = $this->favoriteRepository->add(request()->user()->id, $id, $type);

        return $this->success($favorite);
    }

    public function delete()
    {
        $userId = request()->user()->id;

        $id = request('favoriteable_id');
        $type = request('favoriteable_type');

        if (!$this->favoriteRepository->deleteWhere(['favoriteable_id' => $id, 'favoriteable_type' => $type, 'user_id', $userId])) {
            return $this->failed('删除收藏失败');
        }

        return $this->success();
    }

    public function delFavs()
    {
        $ids = request('ids');
        $favorite = $this->favoriteRepository->delFavorites(request()->user()->id, $ids);
        if ($favorite) {
            return $this->success();
        }

        return $this->failed('删除失败');
    }

    public function getIsFav()
    {
        $id = request('favoriteable_id');
        $type = request('favoriteable_type');
        $isFav = $this->favoriteRepository->isFavorite(request()->user()->id, $id, $type);

        return $this->success([
            'is_Fav' => $isFav ? 1 : 0,
        ]);
    }
}
