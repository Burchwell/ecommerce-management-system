<?php

namespace App\Models;

use App\Models\Orders\Adjustment;
use App\Models\Orders\OrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @package App\Models
 * @property int $id
 * @property string $source
 * @property string $order_id
 * @property string $order_number
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zipcode
 * @property string|null $fulfillment_by
 * @property string $status
 * @property string|null $purchased_at
 * @property string|null $shipped_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Adjustment|null $adjustment
 * @property-read \Illuminate\Database\Eloquent\Collection|OrderItem[] $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Label|null $label
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Query\Builder|Order onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereFulfillmentBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePurchasedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereZipcode($value)
 * @method static \Illuminate\Database\Query\Builder|Order withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Order withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $shipstation_id
 * @property int|null $shipped_from
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShippedFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereShipstationId($value)
 */
class Order extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function adjustment() {
        return $this->morphOne(Adjustment::class, 'adjustable');
    }

    public function label() {
        return $this->hasOne(Label::class, 'order_number', 'order_number');
    }
}
