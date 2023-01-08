<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Factories\AuditTrailsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\AuditTrail
 *
 * @property string $id
 * @property string|null $user_id
 * @property string $title
 * @property string|null $link
 * @property string|null $reference_id
 * @property string $section
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\AuditTrailsFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail newQuery()
 * @method static \Illuminate\Database\Query\Builder|AuditTrail onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail query()
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AuditTrail whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|AuditTrail withTrashed()
 * @method static \Illuminate\Database\Query\Builder|AuditTrail withoutTrashed()
 * @mixin \Eloquent
 */
class AuditTrail extends Model
{
    use HasUuid;
    use SoftDeletes;
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): AuditTrailsFactory
    {
        return AuditTrailsFactory::new();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
