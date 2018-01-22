<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole;
use DB;
class Role extends EloquentRole
{
    protected $fillable = [
        'name',
        'description',
        'slug',
        'permissions',
    ];
    public static function role($user_id)
    {
    	# code...
    	$role_id= DB::table('role_users')->where('user_id',$user_id)->pluck('role_id');
    	$role= Role::find($role_id)->description;
    	return $role;

    }
}
