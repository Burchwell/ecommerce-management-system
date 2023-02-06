<?php

namespace App\Models\Warehouse;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EodChecklistEodTask
 *
 * @package App\Models\Warehouse
 * @property int $id
 * @property int $eod_checklist_id
 * @property int $eod_task_id
 * @property string $completedBy
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Warehouse\EodTask[] $tasks
 * @property-read int|null $tasks_count
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask query()
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask whereCompletedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask whereEodChecklistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask whereEodTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EodChecklistEodTask whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EodChecklistEodTask extends Model
{
    protected $fillable = ['eod_checklist_id', 'eod_task_id', 'completedBy'];

    protected $table = 'eod_checklist_eod_task';

    protected $with = ['tasks'];

    public function tasks() {
        return $this->hasMany(EodTask::class);
    }
}
