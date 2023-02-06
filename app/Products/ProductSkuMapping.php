<?php

namespace App\Products;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductSkuMapping
 *
 * @package App\Products
 * @property int $id
 * @property int $product_id
 * @property string $mapping
 * @property string|null $source
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping whereMapping($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSkuMapping whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductSkuMapping extends Model
{
    /** @var string[]  */
    protected $fillable = ['product_id', 'mapping', 'source'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product() {
        return $this->belongsTo(Product::class);
    }
}
