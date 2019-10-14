<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018-06-27
 * Time: 13:26
 */

namespace GuoJiangClub\Component\Discount\Test\Repositories;

use GuoJiangClub\Component\Discount\Repositories\DiscountRepository;

class DiscountRepositoryTest extends \GuoJiangClub\Component\Discount\Test\BaseTest
{
    public function testFindActive()
    {
        $repository =$this->app->make(DiscountRepository::class);

        $discounts = $repository->findActive();

        $this->assertEquals(3,count($discounts));


        $repository =$this->app->make(DiscountRepository::class);

        $discounts = $repository->findActive(1);

        $this->assertEquals(4,count($discounts));
    }
}