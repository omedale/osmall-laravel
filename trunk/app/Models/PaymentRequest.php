<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'paymentrequest';
    protected $guarded = [ 'id'];
}
