<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EodTask
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodTask whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EodTask extends Model
{
    protected $fillable = ['name', 'description'];
}
