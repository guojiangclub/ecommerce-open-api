<?php

/*
 * This file is part of ibrand/category.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GuoJiangClub\Component\Category\Test;


class CategoryRepositoryTest extends BaseTest
{
    public function testGetSubIdsById()
    {

        //get Sub Ids By Id

        $this->assertSame(0, count($this->repository->getSubIdsById(10, true)));

        $this->repository->skipCache(true);

        $category_id_1 = $this->repository->getSubIdsById(1);

        $this->assertSame(8, count($category_id_1));

        $this->assertSame(2, $category_id_1[1]);

        $category_id_1 = $this->repository->getSubIdsById(1, true);

        $this->assertSame(7, count($category_id_1));

        $this->assertSame(3, $category_id_1[1]);


    }

    public function testGetCategories()
    {

        //get Categories tree

        $tree = $this->repository->getCategories();

        $this->assertSame(1, count($tree));

        $this->assertSame('女装', $tree->first()->name);

        $children = $tree->first()->children;

        $this->assertSame(3, $children->count());

        $this->assertSame('2017卫衣', $children->first()->children->first()->name);

        $this->repository->create(['name' => '2017短裤', 'sort' => 1], 5);

        $this->repository->create(['name' => '男装', 'sort' => 1]);

        $tree = $this->repository->getCategories();

        $this->assertSame(2, count($tree));

        $this->assertSame('男装', $tree->toArray()[1]['name']);

        $this->repository->skipCache(true);

        $tree = $this->repository->getCategories();

        $this->assertSame('2017短裤', last($tree->first()->children->toArray()[2]['children'])['name']);

        $tree = $this->repository->getCategories(1);

        $this->assertEquals(2,$tree->count());

    }

    public function testGetSubCategoriesByNameOrId()
    {

        //getSubCategoriesByNameOrId

        $this->repository->create(['name' => '男装', 'sort' => 1]);

        $all = $this->repository->getSubCategoriesByNameOrId(1, 1)->toArray();

        $this->repository->skipCache(true);

        $this->assertSame(3, count($all));

        $this->assertSame([], $all[0]['children']);

        $all = $this->repository->getSubCategoriesByNameOrId(2)->toArray();

        $this->assertSame(2, count($all));

        $all = $this->repository->getSubCategoriesByNameOrId(18)->toArray();

        $this->assertSame('男装', last($all)['name']);


    }
}
