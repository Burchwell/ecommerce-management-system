<?php

namespace App\Models\Shopify;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Shopify\CustomerAddress
 *
 * @property int $id
 * @property string $customer_id
 * @property string $source_id
 * @property string $company
 * @property string $name
 * @property string $phone
 * @property string $address
 * @property string $address2
 * @property string $city
 * @property string $zip
 * @property string $province_code
 * @property string $country_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereAddress2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereProvinceCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereSourceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerAddress whereZip($value)
 * @mixin \Eloquent
 */
class CustomerAddress extends Model
{
    //
}
