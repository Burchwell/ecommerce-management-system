<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Warehouse\EodChecklist
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Warehouse\EodTask[] $completed_tasks
 * @property-read int|null $completed_tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist query()
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklist whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EodChecklist extends Model
{
    protected $fillable = ['name'];

    public function completed_tasks() {
        return $this->belongsToMany(EodTask::class)->withPivot('completedBy');
    }
}
