<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Database\Factories\SentEmailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SentEmail
 *
 * @property string $id
 * @property string|null $date
 * @property string|null $from
 * @property string|null $to
 * @property string|null $cc
 * @property string|null $bcc
 * @property string|null $subject
 * @property string $body
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\SentEmailFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail query()
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereBcc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereCc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SentEmail whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SentEmail extends Model
{
    use HasFactory;
    use HasUuid;

    protected $guarded = [];

    protected static function newFactory(): SentEmailFactory
    {
        return SentEmailFactory::new();
    }
}
