<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['code','amount','percent','type','valid_until','active'];
    protected $casts = ['valid_until' => 'date'];

    public function isValid(): bool
    {
        if (!$this->active) return false;
        if ($this->valid_until && now()->greaterThan($this->valid_until)) return false;
        return true;
    }
}