<?php

namespace App\Models\Roles;

use App\Models\User;
use Database\Factories\Roles\RoleUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Roles\RoleUser
 *
 * @property int $id
 * @property string $role_id
 * @property string $user_id
 * @property-read \App\Models\Roles\Role|null $role
 * @property-read User|null $user
 * @method static \Database\Factories\Roles\RoleUserFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
 * @mixin \Eloquent
 */
class RoleUser extends Model
{
    use HasFactory;

    protected $guarded    = [];
    public $timestamps = false;
    public $table      = 'role_user';

    protected $casts = [
        'role_id' => 'string',
        'user_id' => 'string'
    ];

    protected static function newFactory(): RoleUserFactory
    {
        return RoleUserFactory::new();
    }

    /**
     * Get the user associated with the RoleUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * Get the role associated with the RoleUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}
