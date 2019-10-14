<?php

namespace GuoJiangClub\EC\Open\Backend\Store\Model;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class SpecsValue extends Model implements Transformable
{
    use TransformableTrait;
    
    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'goods_spec_value');
    }

    public function belongToSpec()
    {
        return $this->belongsTo(Spec::class,'spec_id');
    }

    public function scopeJudge($query, $name, $spec_id, $id = 0)
    {
       return $query->where('name', $name)->where('spec_id', $spec_id)->where('id', '<>' ,$id)->get();
    }
    
}
