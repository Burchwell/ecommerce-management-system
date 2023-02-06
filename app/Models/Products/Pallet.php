<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Products\Pallet
 *
 * @property int $id
 * @property int $product_id
 * @property string|null $sku
 * @property int $totalpcs
 * @property int|null $cartonqty
 * @property string $length
 * @property string $width
 * @property string $height
 * @property string|null $imageurl
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereCartonqty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereImageurl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereTotalpcs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereWidth($value)
 * @mixin \Eloquent
 * @property string|null $notes
 * @method static \Illuminate\Database\Eloquent\Builder|Pallet whereNotes($value)
 */
class Pallet extends Model
{
    //
}
