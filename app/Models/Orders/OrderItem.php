<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Model;

/**
 * Class OrderItem
 *
 * @package App\Models\Orders
 * @property int $id
 * @property int $order_id
 * @property string|null $order_item_id
 * @property string $order_number
 * @property string $sku
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Orders\Adjustment|null $adjustment
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class OrderItem extends Model
{
    protected $fillable = ['order_id', 'order_number', 'product_id', 'variant_id', 'sku', 'quantity'];

    public function adjustment() {
        return $this->morphOne(Adjustment::class, 'adjustable');
    }
}
