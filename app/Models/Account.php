<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use Notifiable;
    
    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'role',
        'password',
        'login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
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
    
    public function isUser()
    {
        return $this->role === 'user';
    }
    
    public function incrementLoginAttempts()
    {
        $this->login_attempts = ($this->login_attempts ?? 0) + 1;
        
        if ($this->login_attempts >= 5) {
            $this->locked_until = \Carbon\Carbon::now()->addSeconds(30);
        }
        
        $this->save();
    }
    
    public function resetLoginAttempts()
    {
        $this->login_attempts = 0;
        $this->locked_until = null;
        $this->save();
    }
    
    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }
    
    public function getRemainingLockSeconds()
    {
        if (!$this->isLocked()) {
            return 0;
        }
        
        return now()->diffInSeconds($this->locked_until, false);
    }
}