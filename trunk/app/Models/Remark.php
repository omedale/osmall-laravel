<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    protected $table = 'remark';

    protected $fillable=['user_id','remark'];

}
