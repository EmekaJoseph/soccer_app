<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SubUserModel extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_subusers';
    protected $primaryKey = 'subuser_id';

    protected $guarded = [];

    protected $hidden = ['password',];

    public $timestamps = false;

    public function relatedUser()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
