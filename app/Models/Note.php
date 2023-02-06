<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Note
 *
 * @package App\Models
 * @property int $id
 * @property int $user_id
 * @property int $notable_id
 * @property int $notable_type
 * @property string|null $subject
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Model|\Eloquent $notable
 * @method static \Illuminate\Database\Eloquent\Builder|Note newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Note query()
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereNotableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereNotableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Note whereUserId($value)
 * @mixin \Eloquent
 */
class Note extends Model
{
    /** @var string[]  */
    protected $fillable = ['notable_id', 'notable_type', 'subject', 'message'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
