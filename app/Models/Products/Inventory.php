<?php

namespace App\Models\Products;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Inventory
 *
 * @package App\Models\Product
 * @property int $id
 * @property int $product_id
 * @property int $warehouse_id
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\Products\Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Inventory whereWarehouseId($value)
 * @mixin \Eloquent
 */
class Inventory extends Model
{

    protected $fillable = ['product_id', 'warehouse_id', 'quantity'];

    public function product(): void
    {
        $this->belongsTo(Product::class);
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
