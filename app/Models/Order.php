<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'subtotal',
        'discount_total',
        'total',
        'discount_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
{
    return $this->belongsToMany(Menu::class)
        ->withPivot('quantity', 'price')
        ->withTimestamps();
}


    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }

    public function recalculateTotals()
    {
        $subtotal = $this->items->sum(fn($item) => $item->pivot->line_total);
        $discountTotal = $this->discount ? $this->discount->amount : 0;
        $total = max($subtotal - $discountTotal, 0);

        $this->update([
            'subtotal' => $subtotal,
            'discount_total' => $discountTotal,
            'total' => $total,
        ]);
    }
    

}
