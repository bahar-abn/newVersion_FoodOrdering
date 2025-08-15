<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','menu_id','rating','note'];

    public function user() { return $this->belongsTo(User::class); }
    public function menu() { return $this->belongsTo(Menu::class); }
}