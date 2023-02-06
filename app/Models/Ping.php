<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ping
 *
 * @property int $id
 * @property string $type
 * @property string $source
 * @property string $last_ping
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Ping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ping query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ping whereLastPing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ping whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ping whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ping whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ping extends Model
{
    protected $fillable = ['type', 'source', 'last'];
}
