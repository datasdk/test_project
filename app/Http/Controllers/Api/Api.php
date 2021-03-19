<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Companies\Companies_privots;
use Laravel\Passport\HasApiTokens;
use Company;
use User;
use Auth;


class Api extends Controller
{

    use HasApiTokens;

    
    public function create_companies(request $request){

    
        $post = $request->all();

        $errors = Company::errors($post);

        if($errors){

            return response()->json(["error"=>true,"msg"=>$errors->first()]); ;

        }
        

        $res = Company::add($post);

  
        $data =  collect($res)
        ->forget(['updated_at','created_at'])
        ->toArray();


        $return = [
            "success"=>true,
            "msg"=>"Company created successfully",
            "data" => $data 
        ];

        
        return $return;

    }


    public function get_companies(request $request){


        $com = Company::all();


        $com->map(function($val,$key){
                 
            
            $val["prettyAddress"] = $val["address"]." ".$val["houseNo"].", ".$val["zipcode"]." ".$val["city"].", ".$val["country"];  
                  
             
            $val["address"] = [
                "street"    => $val["address"],
                "houseNo"   => $val["houseNo"],
                "zipcode"   => $val["zipcode"],
                "city"      => $val["city"],
                "country"   => $val["country"],
            ];
              

            unset($val["houseNo"]);
            unset($val["zipcode"]);
            unset($val["city"]);
            unset($val["country"]);

        
        });


        return $com;

    }




    public function get_clients($id){
               
        $cli = Company::find($id)->get_clients()->get();

        return $cli;
        
    }


    public function attach_clients(Request $request,$company_id){


        $post = $request->all();

        $user_id = explode(",",$post["userIds"]);
        
        $return = [];


        foreach($user_id as $id){

            $res = Companies_privots::add($id,$company_id);

            if($res){

                $return[]= collect($res)->only("id");
    
            }             

        }
        

        return $return;
        
    
    }


    public function update_company(Request $request,$company_id){


        if(!count($request->all())){

            return response()->json(["success"=>false,"msg"=>"Parameters must not be empty"]);
        }


        if(Company::find($company_id)->update( $request->all() )){

            return response()->json(["success"=>true,"msg"=>"company is updated"]);

        }


        return response()->json(["success"=>false,"msg"=>"error"]);

    }


   

}
