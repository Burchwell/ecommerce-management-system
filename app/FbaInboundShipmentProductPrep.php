<?php

namespace App;

use App\Models\Amazon\FbaInboundShipmentProduct;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FbaInboundShipmentProductPrep
 *
 * @package App
 * @property int $id
 * @property int $fba_is_product_id
 * @property string $prep_type
 * @property string $prep_owner
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read FbaInboundShipmentProduct $fbaInboundShipmentProduct
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep query()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep whereFbaIsProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep wherePrepOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep wherePrepType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProductPrep whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FbaInboundShipmentProductPrep extends Model
{
    protected $fillable = ['fba_is_product_id', 'prep_type', 'prep_owner'];
    public function fbaInboundShipmentProduct() {
        return $this->belongsTo(FbaInboundShipmentProduct::class);
    }
}
