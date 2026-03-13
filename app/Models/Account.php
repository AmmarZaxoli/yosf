<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Add this for authentication

class Account extends Authenticatable // Changed to Authenticatable for login functionality
{
    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getRoleColorAttribute()
    {
        $colors = [
            'admin' => '#8b5cf6',
            'user' => '#10b981',
        ];

        return $colors[$this->role] ?? '#6b7280';
    }
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}