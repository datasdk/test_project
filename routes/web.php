<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Company\Company;
use App\Http\Controllers\Users\Users;
use App\Http\Controllers\Api\Api;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get("/",function(){
    
    return view("index");

});


Route::resource("company",Company::class)->name("company","*");

Route::resource("users",Users::class)->name("users","*");





/*

$page = 
Pages::where("url",Pages::replace(Request::path()))
->where("method","get")
->where("static",0)
->first();


if($page){

    $func = 
    function () use($page){


        $url    = $page->url;
        $html   = urldecode($page->html);
        $view   = $page->name;
 
        $folder = __DIR__."/../resources/views/";
        $file   = $view.".blade.php";
        $path   = $folder."/".$file;

    

        if(!file_exists($path)){


            $html_with_wrapper = "
            @extends('layout.app')
            @section('content')
            ".$html."
            @endsection
            ";    


            //file_put_contents($path,$html_with_wrapper);

        }
        

        if (View::exists($view)) {

            return view($view);

        } else {

            echo "View not found";

        }

    };


    Route::get($page->url, $func);

    Route::get(Languages::lang_url()."".$page->url, $func);

}





// INCLUDE


$includes = 
Pages::where("method","include")
->get();


foreach($includes as $include){



    $html   = urldecode($include->html);
    $view   = $include->name;

    $folder = __DIR__."/../resources/views/includes";
    $file   = $view.".blade.php";
    $path   = $folder."/".$file;




    file_put_contents($path,$html);




}


*/
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
