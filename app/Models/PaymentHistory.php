<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_history';
    protected $fillable = ['order_id','user_id','status','gateway_ref','amount','meta'];
    protected $casts = ['meta' => 'array'];

    public function order() { return $this->belongsTo(Order::class); }
    public function user() { return $this->belongsTo(User::class); }
}