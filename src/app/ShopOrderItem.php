<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class ShopOrderItem extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'shop_order_items';

    protected $fillable = [
        'item_id',
        'quantity',
        'shop_order_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    /*
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo('App\ShopOrder', 'shop_order_id');
    }
}
