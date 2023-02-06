<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Label
 *
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $pdf
 * @property int|null $printed
 * @property string|null $print_date
 * @property string $computer_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order|null $order
 * @method static \Illuminate\Database\Eloquent\Builder|Label newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Label newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Label query()
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereComputerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label wherePdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label wherePrintDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label wherePrinted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $order_number
 * @property string|null $printed_at
 * @method static \Illuminate\Database\Eloquent\Builder|Label whereOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Label wherePrintedAt($value)
 */
class Label extends Model
{
    protected $fillable = ['order_number', 'printed', 'printed_at', 'computer_name'];

    public function order() {
        return $this->hasOne(Order::class, 'order_number', 'order_id');
    }
}
