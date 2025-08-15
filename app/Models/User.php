<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role'];
    protected $hidden = ['password','remember_token'];
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function orders() { return $this->hasMany(Order::class); }
    public function comments() { return $this->hasMany(Comment::class); }
    public function surveys() { return $this->hasMany(Survey::class); }
}