<?php

/*
 * This file is part of ibrand/favorite.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Favorite\Test;

use GuoJiangClub\Component\Favorite\Favorite;

class FavoriteTest extends BaseTest
{
    public function testFavoriteModels()
    {
        //Obtaining a user collected
        $list_goods = $this->repository->getByUserAndType(1, 'goods', 15);
        $this->assertEquals(2, count($list_goods));

        $list_goods = $this->repository->getByUserAndType(2, 'goods', 15);
        $this->assertEquals('Illuminate\Pagination\LengthAwarePaginator', get_class($list_goods));
        $this->assertEquals(0, count($list_goods));

        $list_activity = $this->repository->getByUserAndType(2, 'activity', 15);
        $this->assertEquals(3, count($list_activity));

        $isFavorite = $this->repository->isFavorite(1, 1, 'goods');
        $this->assertEquals('GuoJiangClub\Component\Favorite\Favorite', get_class($isFavorite));
        $this->assertEquals('goods', $isFavorite->favoriteable_type);
        $this->assertEquals(1, $isFavorite->favoriteable_id);

        //Whether the judgment has been collected
        $isFavorite = $this->repository->isFavorite(1, 8, 'goods');
        $this->assertNull($isFavorite);

        //User delete or create a collected
        $store = $this->repository->add(1, 8, 'goods');
        $this->assertEquals(8, Favorite::all()->last()->favoriteable_id);

        $del = $this->repository->add(1, 1, 'goods');
        $isFavorite = $this->repository->isFavorite(1, 1, 'goods');
        $this->assertNotNull($isFavorite);

        //Batch deleting
        $isFavorite_2 = $this->repository->isFavorite(1, 2, 'goods');
        $this->assertEquals('GuoJiangClub\Component\Favorite\Favorite', get_class($isFavorite_2));
        $this->assertEquals('goods', $isFavorite_2->favoriteable_type);
        $this->assertEquals(2, $isFavorite_2->favoriteable_id);

        $batch_deleting = $this->repository->delFavorites('1', [2, 3]);
        $isFavorite_2 = $this->repository->isFavorite(1, 2, 'goods');
        $this->assertNull($isFavorite_2);
        $isFavorite_3 = $this->repository->isFavorite(1, 3, 'goods');
        $this->assertNull($isFavorite_3);
    }
}
