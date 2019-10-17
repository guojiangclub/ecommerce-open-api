<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('collect_to_array')) {
    /**
     * @param $collection
     *
     * @return array
     */
    function collect_to_array($collection)
    {
        $array = [];
        foreach ($collection as $item) {
            $array[] = $item;
        }

        return $array;
    }
}
