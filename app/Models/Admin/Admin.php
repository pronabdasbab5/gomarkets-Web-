<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';
    protected $table = 'admin';

    protected $fillable = [
        'name', 'email', 'contact_no', 'password', 'login_time', 'logout_time'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
