<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VulnerableUser extends Model
{
    protected $table = 'vulnerable_users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
    ];

    protected $casts = [
    ];

    public function verifyPassword($inputPassword)
    {
        return $this->password === $inputPassword;
    }

    public function getStoredPassword()
    {
        return $this->password;
    }
}