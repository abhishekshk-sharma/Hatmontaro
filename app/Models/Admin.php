<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        if (empty($password)) {
            return;
        }

        // If it is already a valid bcrypt or argon hash, do not hash it again!
        if (is_string($password) && (
            str_starts_with($password, '$2y$') ||
            str_starts_with($password, '$2a$') ||
            str_starts_with($password, '$2b$') ||
            str_starts_with($password, '$argon2i$') ||
            str_starts_with($password, '$argon2id$')
        )) {
            $this->attributes['password'] = $password;
            return;
        }

        $this->attributes['password'] = Hash::make($password);
    }

    public function getAuthPassword()
    {
        $password = $this->password;
        if (is_string($password) && (str_starts_with($password, '$2b$') || str_starts_with($password, '$2a$'))) {
            return '$2y$' . substr($password, 4);
        }
        return $password;
    }
}
