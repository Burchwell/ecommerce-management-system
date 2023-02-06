<?php

namespace App\Shipstation;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Shipstation\ShipstationTag
 *
 * @property int $id
 * @property string $tag_id
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag query()
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag whereTagId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $autoprint_trigger_tag
 * @method static \Illuminate\Database\Eloquent\Builder|ShipstationTag whereAutoprintTriggerTag($value)
 */
class ShipstationTag extends Model
{
    //
}
