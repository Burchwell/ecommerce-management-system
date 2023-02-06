<?php

namespace App\Models\Orders;

use App\Models\Note;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Orders\Adjustment
 *
 * @property-read Model|\Eloquent $adjustable
 * @property-read \Illuminate\Database\Eloquent\Collection|Note[] $notes
 * @property-read int|null $notes_count
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $adjustable_type
 * @property string $adjustable_id
 * @property int|null $adjustment_id
 * @property string $adjustable_order_number
 * @property string|null $reason
 * @property int $is_restock
 * @property string $quantity
 * @property string|null $type
 * @property string $amount
 * @property string $tax
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereAdjustableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereAdjustableOrderNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereAdjustableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereAdjustmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereIsRestock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Adjustment whereUpdatedAt($value)
 */
class Adjustment extends Model
{
    protected $fillable = ['adjustable_type', 'adjustable_id', 'adjustable_order_number', 'reason', 'is_restock', 'quantity', 'type', 'amount', 'tax'];

    public function adjustable() {
        return $this->morphTo();
    }

    public function notes() {
        return $this->morphMany(Note::class, 'notable');
    }
}
