<?php

namespace App\Models\Products;

use App\Models\Image;
use App\Models\Note;
use App\Models\Order;
use App\Models\Orders\OrderItem;
use App\Products\ProductSkuMapping;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


/**
 * Class Product
 *
 * @package App\Models\Product
 * @property int $id
 * @property int|null $shopify_id
 * @property int|null $channel_advisor_id
 * @property int|null $shipstation_id
 * @property string $sku
 * @property string|null $upc
 * @property string|null $asin
 * @property int|null $qpmc
 * @property string|null $weight
 * @property string|null $length
 * @property string|null $width
 * @property string|null $height
 * @property string|null $location
 * @property string $active
 * @property string|null $product_image
 * @property string|null $cost
 * @property string|null $price
 * @property int|null $afn_fulfillable_qty
 * @property int|null $afn_warehouse_qty
 * @property int|null $afn_unsellable_qty
 * @property int|null $afn_reserved_qty
 * @property int|null $afn_total_qty
 * @property int|null $afn_per_unit_volume
 * @property int|null $afn_inbound_working_qty
 * @property int|null $afn_inbound_shipped_qty
 * @property int|null $afn_inbound_receiving_qty
 * @property int|null $afn_researching_qty
 * @property int|null $afn_reserved_future_supply
 * @property int|null $afn_future_supply_buyable
 * @property int|null $fba_reserved_qty
 * @property int|null $fba_reserved_customer_orders
 * @property int|null $fba_reserved_fc_transfers
 * @property int|null $fba_reserved_fc_processing
 * @property int|null $total_turn
 * @property string|null $total_fees_estimate
 * @property string|null $total_profit_estimate
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $store_active
 * @property int|null $fba_active
 * @property-read string $barcode
 * @property-read \Illuminate\Database\Eloquent\Relations\HasMany $product_sales
 * @property-read mixed $test_var
 * @property-read \Illuminate\Database\Eloquent\Collection|Image[] $images
 * @property-read int|null $images_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\Inventory[] $inventory
 * @property-read int|null $inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\ProductMeta[] $meta
 * @property-read int|null $meta_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Note[] $notes
 * @property-read int|null $notes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|OrderItem[] $orderItems
 * @property-read int|null $order_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Order[] $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Products\Pallet|null $pallet
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\ProductTurnAround[] $turnAround
 * @property-read int|null $turn_around_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\ProductVariant[] $variants
 * @property-read int|null $variants_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Query\Builder|Product onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnFulfillableQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnFutureSupplyBuyable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnInboundReceivingQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnInboundShippedQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnInboundWorkingQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnPerUnitVolume($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnResearchingQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnReservedFutureSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnReservedQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnTotalQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnUnsellableQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAfnWarehouseQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereAsin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereChannelAdvisorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFbaActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFbaReservedCustomerOrders($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFbaReservedFcProcessing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFbaReservedFcTransfers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFbaReservedQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLength($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereProductImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQpmc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShipstationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereShopifyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereStoreActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTotalFeesEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTotalProfitEstimate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereTotalTurn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereWidth($value)
 * @method static \Illuminate\Database\Query\Builder|Product withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Product withoutTrashed()
 * @mixin \Eloquent
 * @property string $qb_sku
 * @property int|null $max_fba_mc_qty
 * @property string|null $is_finished_bundle
 * @property string|null $fba_item_notes
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductSkuMapping[] $skuMappings
 * @property-read int|null $sku_mappings_count
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereFbaItemNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsFinishedBundle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereMaxFbaMcQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereQbSku($value)
 */
class Product extends Model
{
    use SoftDeletes;

    /** @var string[] */
    protected $guarded = ['id'];

    public static $clean = [
        '3CH-BNDL-(2) ',
        'DLR-'
    ];

    public static $filter = [
        ['sku', 'NOT LIKE', '%3CH-%'],
        ['sku', 'NOT LIKE', '%DLR-%'],
        ['sku', 'NOT LIKE', '%RFBISH-%'],
        ['sku', 'NOT LIKE', '%USED%'],
        ['sku', 'NOT LIKE', '%TPA1-%'],
        ['sku', 'NOT LIKE', '%PROTOTYPE-%'],
        ['sku', 'NOT LIKE', '%NEW-BSTOCK%'],
    ];

//    protected $appends = ['product_sales'];

    public function meta()
    {
        return $this->hasMany(ProductMeta::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Inventory::class);
    }

    public function turnAround()
    {
        return $this->hasMany(ProductTurnAround::class)->with('warehouse');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pallet()
    {
        return $this->hasOne(Pallet::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images()
    {
        return $this->morphMany(Image::class, 'imagable');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items', 'id', 'order_id', 'sku', 'sku');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'sku', 'sku');
    }

    public function skuMappings()
    {
        return $this->hasMany(ProductSkuMapping::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function notes(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Note::class, 'notable');
    }

    public function getTestVarAttribute()
    {
        return "Hello world!";
    }

    /**
     * @return string
     */
    public static function getBarcodeAttribute($upc = null)
    {
        $digits = $upc ?: self::upc;
        $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
        $even_sum_three = $even_sum * 3;
        $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
        $total_sum = $even_sum_three + $odd_sum;
        $next_ten = (ceil($total_sum / 10)) * 10;
        $check_digit = $next_ten - $total_sum;
        return (int)$digits . $check_digit;
    }
}
