<?php

namespace App\Models\Companies;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Validator;
use Company;


class Companies extends Model
{

    use HasFactory;


    protected $fillable = [
        "name",
        "vat",
        "address",
        "houseno",
        "zipcode",
        "city",
        "country",
        "phone",
    ];



    public static function add($props){  

        return self::create($props);
        
    }

    public static function errors($props){  


        $validator = 
        Validator::make($props, [
            "name"       => "required",
            "vat"        => "required",
            "address"    => "required",
            "houseno"    => "required",
            "zipcode"    => "required",
            "city"       => "required",
            "country"    => "required",
            "phone"      => "required",
        ]);


        if ($validator->fails()) {

            return $validator->errors();
        }


        return false;

    }


    public function get_clients(){

        return $this->belongsToMany(User::class,"companies_privots");

    }


   

}
