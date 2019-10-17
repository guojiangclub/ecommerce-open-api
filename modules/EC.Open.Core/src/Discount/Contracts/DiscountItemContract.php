<?php

/*
 * This file is part of ibrand/core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\EC\Open\Core\Discount\Contracts;

interface DiscountItemContract
{
    /**
     * get item key code.
     *
     * @return int
     */
    public function getKeyCode($type = 'sku');

    /**
     * get item relation key codes.
     *
     * @return mixed
     */
    public function getChildKeyCodes();

    /**
     * get item type.
     *
     * @return string
     */
    public function getItemType();

    /**
     * get item categories.
     *
     * @return mixed
     */
    public function getCategories();
}
