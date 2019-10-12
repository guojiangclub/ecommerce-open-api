<?php
namespace GuoJiangClub\EC\Open\Backend\Album\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{   
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_') . 'images');
    }

}