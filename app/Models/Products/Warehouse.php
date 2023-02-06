<?php

namespace App\Models\Products;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Warehouse
 *
 * @package App\Models\Product
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $shipstation_id
 * @property string|null $description
 * @property string|null $address
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $postalCode
 * @property string|null $country
 * @property int|null $return_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Products\Inventory[] $inventory
 * @property-read int|null $inventory_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Note[] $notable
 * @property-read int|null $notable_count
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereReturnTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereShipstationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Warehouse whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'slug', 'shipstation_id', 'description', 'address', 'address2', 'city', 'state', 'postCode', 'country', 'return_to'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notable(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Inventory::class);
    }
}
