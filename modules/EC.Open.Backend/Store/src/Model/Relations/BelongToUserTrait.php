<?php


namespace iBrand\EC\Open\Backend\Store\Model\Relations;
use iBrand\Component\User\Models\User;

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
        return $this->belongsTo(User::class);
    }

}
