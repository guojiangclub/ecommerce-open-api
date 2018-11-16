<?php


namespace iBrand\EC\Open\Backend\Store\Model\Relations;

/**
 * This is the has many pages trait.
 *
 * @author Graham Campbell <graham@mineuk.com>
 */
trait BelongToUserTrait
{
    /**
     * Get the page relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneOrMany
     */
    public function user()
    {
        return $this->belongsTo('ElementVip\Component\User\Models\User');
    }

}
