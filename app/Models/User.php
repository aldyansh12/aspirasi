<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'full_name',
        'email',
        'otp_code',
        'otp_expires_at',
        'roles',
        'password',
        'kelas',
    ];
    
    protected $casts = [
        'otp_expires_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            //
        ];
    }

    public function aspirasis()
    {
        return $this->hasMany(\App\Models\aspirasi::class, 'user_id');
    }

    public function kategoris()
    {
        return $this->hasMany(\App\Models\Kategori::class, 'admin_id');
    }
}
