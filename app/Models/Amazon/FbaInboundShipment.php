<?php

namespace App\Models\Amazon;

use App\FbaWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FbaInboundShipment
 *
 * @property int $id
 * @property string $shipment_id
 * @property string $destination_fulfillment_center_id
 * @property string|null $label_prep_type
 * @property int $ship_from_warehouse_id
 * @property string|null $box_content_source
 * @property string $shipment_status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereBoxContentSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereDestinationFulfillmentCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereLabelPrepType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereShipFromWarehouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereShipmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereShipmentStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $shipment_name
 * @property-read mixed $shipment_appendix
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Amazon\FbaInboundShipmentProduct[] $items
 * @property-read int|null $items_count
 * @property-read FbaWarehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|FbaInboundShipment whereShipmentName($value)
 */
class FbaInboundShipment extends Model
{
    protected $table = 'fba_inbound_shipments';
    protected $fillable = ['shipment_id'];
    protected $appends = ['shipment_appendix'];

    public function items() {
        return $this->hasMany(FbaInboundShipmentProduct::class, 'fba_inbound_shipment_id', 'id');
    }

    public function warehouse() {
        return $this->belongsTo(FbaWarehouse::class, 'destination_fulfillment_center_id', 'fba_code');
    }

    public function getShipmentAppendixAttribute() {
        foreach ($this->items as $item) {
            $this->total_cbm += ((($item->product->length * 0.0254) * ($item->product->width * 0.0254) * ($item->product->height * 0.0254)) * $item->quantity_shipped);
            $this->total_weight += ($item->product->weight * $item->quantity_shipped);
        }

        $values = [
            'total_weight' => $this->total_weight,
            'total_cbm' => $this->total_cbm
        ];

        return $values;
    }
}
