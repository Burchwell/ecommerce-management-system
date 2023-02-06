<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FreightLabels
 *
 * @property int $id
 * @property string $po_number
 * @property int $carrier_id
 * @property string $vendor
 * @property string $pickup_at
 * @property int $pallets
 * @property string $address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels query()
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels whereCarrierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels wherePallets($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels wherePickupAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels wherePoNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FreightLabels whereVendor($value)
 * @mixin \Eloquent
 */
class FreightLabels extends Model
{
    //
}
