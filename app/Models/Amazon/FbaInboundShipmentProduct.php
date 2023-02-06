<?php

namespace App\Models\Amazon;

use App\FbaInboundShipmentProductPrep;
use App\FbaWarehouse;
use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FbaInboundShipmentProduct
 *
 * @property int $id
 * @property int $fba_inbound_shipment_id
 * @property string $product_id
 * @property int $quantity_shipped
 * @property int $quantity_received
 * @property int $quantity_in_case
 * @property string $release_date
 * @property string $fulfillment_network_sku
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereFbaInboundShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereFulfillmentNetworkSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereQuantityInCase($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereQuantityReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereQuantityShipped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereReleaseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $sku
 * @property string|null $taped_by
 * @property string|null $labeled_by
 * @property-read mixed $cubic_meters
 * @property-read mixed $total_weight
 * @property-read \Illuminate\Database\Eloquent\Collection|FbaInboundShipmentProductPrep[] $prep
 * @property-read int|null $prep_count
 * @property-read Product|null $product
 * @property-read \App\Models\Amazon\FbaInboundShipment $shipment
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereLabeledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipmentProduct whereTapedBy($value)
 */
class FbaInboundShipmentProduct extends Model
{
    protected $fillable = ['fba_inbound_shipment_id','product_id'];

    protected $appends = ['cubic_meters', 'total_weight'];

    private $product = null;

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function prep() {
        return $this->hasMany(FbaInboundShipmentProductPrep::class, 'fba_is_product_id', 'id');
    }

    public function shipment() {
        return $this->belongsTo(FbaInboundShipment::class, 'fba_inbound_shipment_id', 'id')
            ->with('warehouse');
    }

    public function getCubicMetersAttribute() {
        $product = Product::where('sku', '=', $this->sku)->first();
        if (is_object($product)) {
            return number_format((($product->length * 0.0254) * ($product->width * 0.0254) * ($product->height * 0.0254)), 2);
        }
        return 0;
    }

    public function getTotalWeightAttribute() {
        $product = Product::where('sku', '=', $this->sku)->first();
        if (is_object($product)) {
            return number_format(($product->weight * $this->quantity_shipped), 2);
        }
        return 0;
    }
}
