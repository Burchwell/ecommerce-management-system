<?php

namespace App;

use App\Models\Products\Product;
use Illuminate\Database\Eloquent\Model;

/**
 * App\FbaRestockInventoryRecommendations
 *
 * @property int $id
 * @property int $product_id
 * @property string $sales_last_thirty
 * @property string $units_sold_thirty
 * @property string $total_units
 * @property string $inbound
 * @property string $available
 * @property string $fc_transfer
 * @property string $fc_processing
 * @property string $customer_order
 * @property string $unfulfillable
 * @property string $days_of_supply
 * @property string $alert
 * @property string $recommended_replenishment_qty
 * @property string $recommended_ship_date
 * @property string $current_month_inventory_level_thresholds_published Current Month
 * @property string $current_month_very_low_inventory_threshold
 * @property string $current_month_minimum_inventory_threshold
 * @property string $current_month_maximum_inventory_threshold
 * @property string $current_month_very_high_inventory_threshold
 * @property string $next_month_inventory_level_thresholds_published Next Month
 * @property string $next_month_very_low_inventory_threshold
 * @property string $next_month_minimum_inventory_threshold
 * @property string $next_month_maximum_inventory_threshold
 * @property string $next_month_very_high_inventory_threshold
 * @property string $utilization
 * @property string $maximum_shipment_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations query()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereAlert($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCurrentMonthInventoryLevelThresholdsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCurrentMonthMaximumInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCurrentMonthMinimumInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCurrentMonthVeryHighInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCurrentMonthVeryLowInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereCustomerOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereDaysOfSupply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereFcProcessing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereFcTransfer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereInbound($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereMaximumShipmentQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereNextMonthInventoryLevelThresholdsPublished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereNextMonthMaximumInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereNextMonthMinimumInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereNextMonthVeryHighInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereNextMonthVeryLowInventoryThreshold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereRecommendedReplenishmentQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereRecommendedShipDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereSalesLastThirty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereTotalUnits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereUnfulfillable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereUnitsSoldThirty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaRestockInventoryRecommendations whereUtilization($value)
 * @mixin \Eloquent
 */
class FbaRestockInventoryRecommendations extends Model
{
    protected $guarded = ['id'];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
