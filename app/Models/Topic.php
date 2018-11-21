<?php
/**
 * Created by PhpStorm.
 * User: Hong
 * Date: 2018/5/3
 * Time: 16:10
 * Function:
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'topic_items', 'topic_id', 'item_id');
    }
}