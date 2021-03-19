<?php

namespace App\Models\Companies;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies_privots extends Model
{
    use HasFactory;


    protected $fillable = [
        "user_id",
        "companies_id"
    ];



    public static function add($user_id,$company_id){


        if(!self::exists($user_id,$company_id)){


            return
            self::create([
                "user_id" => $user_id,
                "companies_id" => $company_id
            ]);


        }

        return false;
        
    }


    public static function exists($user_id,$company_id){

       
        return 
        self::where("user_id",$user_id)
        ->where("companies_id",$company_id)
        ->exists();

    }

    
}
