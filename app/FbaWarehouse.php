<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FbaWarehouse
 *
 * @property int $id
 * @property string $name
 * @property string $fba_code
 * @property string $address
 * @property string $city
 * @property string|null $state
 * @property string $zipcode
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereFbaCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FbaWarehouse whereZipcode($value)
 * @mixin \Eloquent
 */
class FbaWarehouse extends Model
{
    //
}
