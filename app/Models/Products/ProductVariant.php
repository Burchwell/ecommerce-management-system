<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductVariant
 *
 * @package App\Models\Product
 * @property int $id
 * @property int $product_id
 * @property int $shopify_id
 * @property string $title
 * @property string $sku
 * @property string $barcode
 * @property string $price
 * @property string $compare_at_price
 * @property string $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Products\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereCompareAtPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereShopifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $weight
 * @property string|null $weight_unit
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductVariant whereWeightUnit($value)
 */
class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'shopify_id', 'title', 'sku', 'barcode', 'price', 'compare_at_price', 'image_id', 'weight', 'weight_unit'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
