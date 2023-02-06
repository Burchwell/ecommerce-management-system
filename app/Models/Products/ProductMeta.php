<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductMeta
 *
 * @package App\Models\Products
 * @property int $id
 * @property int $product_id
 * @property string $key
 * @property string $value_type
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Products\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductMeta whereValueType($value)
 * @mixin \Eloquent
 */
class ProductMeta extends Model
{
    protected $fillable = ['product_id', 'key', 'value'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
