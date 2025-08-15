<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $fillable = ['name','description','price','average_rating'];

    public function comments() { return $this->hasMany(Comment::class); }
    public function surveys() { return $this->hasMany(Survey::class); }
    public function updateAverageRating()
    {
        $this->average_rating = $this->surveys()->avg('rating') ?? 0;
        $this->save();
    }
    public function orders() { return $this->belongsToMany(Order::class, 'order_items')->withPivot(['quantity','unit_price','line_total']); }
}