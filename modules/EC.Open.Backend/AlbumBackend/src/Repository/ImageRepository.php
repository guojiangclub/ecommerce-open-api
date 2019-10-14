<?php
namespace GuoJiangClub\EC\Open\Backend\Album\Repository;

use GuoJiangClub\EC\Open\Backend\Album\Models\Image;
use Prettus\Repository\Eloquent\BaseRepository;

class ImageRepository extends BaseRepository
{

    public function model()
    {
        return Image::class;
    }
    public function getImgList()
    {
        
    }
}