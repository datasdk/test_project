<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Companies\Companies_privots as CompanyPrivots;
use Laravel\Passport\HasApiTokens;

use Spatie\Permission\Traits\HasRoles;
use Auth;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'email',
        'phone',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public static function id(){

        return Auth::id();

    }
    

    public static function me(){

        return Auth::user();

    }


    public function has_company(){

        return $this->hasOne(CompanyPrivots::class,"user_id")->exists();

    }


    public static function hasRole($role){
        
        return $this->hasRole($role)->get();


    }

}
