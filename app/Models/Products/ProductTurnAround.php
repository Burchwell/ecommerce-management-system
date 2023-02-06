<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Reports
 *
 * @package App\Products
 * @property int $id
 * @property int|null $product_id
 * @property int|null $warehouse_id
 * @property int|null $quantity
 * @property int|null $days
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Products\Product|null $product
 * @property-read \App\Models\Products\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductTurnAround whereWarehouseId($value)
 * @mixin \Eloquent
 */
class ProductTurnAround extends Model
{
    protected $fillable = ['product_id', 'warehouse_id', 'quantity', 'days'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }
}
