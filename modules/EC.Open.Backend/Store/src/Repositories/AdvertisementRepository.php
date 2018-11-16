<?php

namespace iBrand\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use iBrand\EC\Open\Backend\Store\Model\Advertisement;

/**
 * Class AdvertisementRepository
 * @package namespace App\Repositories;
 */
class AdvertisementRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Advertisement::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
