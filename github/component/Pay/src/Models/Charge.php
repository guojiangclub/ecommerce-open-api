<?php

/*
 * This file is part of ibrand/pay.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Component\Pay\Models;

use Hidehalo\Nanoid\Client;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    protected $table = 'ibrand_pay_charge';

    protected $guarded = ['id'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $client = new Client();

        $this->charge_id = 'ch_'.$client->generateId($size = 24);
    }

    public function setMetadataAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['metadata'] = json_encode($value);
        }
    }

    public function setExtraAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['extra'] = json_encode($value);
        }
    }

    public function getMetadataAttribute($value)
    {
        if (!empty($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }

    public function getExtraAttribute($value)
    {
        if (!empty($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }

    public function setCredentialAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['credential'] = json_encode($value);
        }
    }

    public function getCredentialAttribute($value)
    {
        if (!empty($value)) {
            $value = json_decode($value, true);
        }

        return $value;
    }
}
