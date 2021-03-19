<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use auth;


class LoginController extends Controller
{
    //
    public function login(Request $req){


        $login = 
        $req->validate([
            "email" => "required|string",
            "password" => "required|string"
        ]);


        
        if (!Auth::attempt($login)) {
        
            return response(["success"=>false,"msg"=>"Login is invalid"], 401);

        }



        $user = Auth::user();

        $token = $user->createToken('loginToken');


        return 
        response([
            "success"       => true,
            "msg"           => "user is logged in",
            "accessToken"   => $token->accessToken,
        ]);
        

    }


  
}
