<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Database\Factories\NotificationFactory;

/**
 * App\Models\Notification
 *
 * @property string $id
 * @property string $title
 * @property string $assigned_to_user_id
 * @property string $assigned_from_user_id
 * @property string|null $link
 * @property int|null $viewed
 * @property string|null $viewed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $assignedFrom
 * @property-read \App\Models\User|null $assignedTo
 * @method static \Database\Factories\NotificationFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereAssignedFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereAssignedToUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereViewed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereViewedAt($value)
 * @mixin \Eloquent
 */
class Notification extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    protected static function newFactory(): NotificationFactory
    {
        return NotificationFactory::new();
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function assignedFrom(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_from_user_id');
    }
}
