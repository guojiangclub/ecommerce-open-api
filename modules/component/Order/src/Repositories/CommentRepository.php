<?php

/*
 * This file is part of ibrand/order.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Order\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface CommentRepository extends RepositoryInterface
{
    /**
     * get recommend comments by item id.
     *
     * @param $itemId
     *
     * @return mixed
     */
    public function getRecommendByItem($itemId);
}
