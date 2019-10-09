<?php

namespace iBrand\EC\Open\Backend\Store\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use iBrand\Component\Advert\Models\MicroPage;

class MicroPageRepository extends BaseRepository
{
    public function model()
    {
        return MicroPage::class;
    }

}