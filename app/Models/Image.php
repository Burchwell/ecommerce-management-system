<?php

namespace App\Models;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Image
 *
 * @package App\Models
 * @property int $id
 * @property int $imagable_id
 * @property string $imagable_type
 * @property string $alt
 * @property int $width
 * @property int $height
 * @property int $src
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $product
 * @method static \Illuminate\Database\Eloquent\Builder|Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereImagableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereImagableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereSrc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereWidth($value)
 * @mixin \Eloquent
 * @property string $url
 * @property string|null $filename
 * @property string|null $path
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereFilename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Image whereUrl($value)
 */
class Image extends Model
{
    protected $guarded = ['id'];

    public function product() {
        return $this->morphTo();
    }
}
