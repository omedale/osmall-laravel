<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $table = 'deliveryorder';
    
    protected $fillable=['porder_id','employee_id','status','password'];
    
    public function products()
    {
        return $this->hasMany('App\Models\DeliveryOrderProduct','do_id','id');
    }
    
    public function porder()
    {
        return $this->belongsTo('App\Models\POrder');
    }
    
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee');
    }

    public function setPorder_idAttribute($value) {
        $this->attributes['porder_id'] = sprintf('%06d', $value);
    }
}
