<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','status','subtotal','discount_total','total'];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function menu() { return $this->belongsToMany(Menu::class, 'order_items')->withPivot(['quantity','unit_price','line_total']); }
    public function payment() { return $this->hasOne(PaymentHistory::class); }
}