<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Roles\PermissionRole
 *
 * @property int $id
 * @property string $permission_id
 * @property string $role_id
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole wherePermissionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole whereRoleId($value)
 * @mixin \Eloquent
 */
class PermissionRole extends Model
{
    protected $guarded    = [];
    public $timestamps = false;
    public $table      = 'permission_role';

    protected $casts = [
        'permission_id' => 'string',
        'role_id'       => 'string'
    ];
}
