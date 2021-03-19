<?php

    
    namespace App\Models\Google_maps;

    use App\Models\Companies\Companies;
    use DB;



    Class Google_maps{


        public static function insert($arr = []){

            
            $height = 400;

            $zoom = 15;

            extract($arr);

            // GET API KEY
            
            $sql = "select * from google_console_api limit 1";
            
            $result = DB::table("google_console_api")->first();

            $api_key = trim($result->api_key);


            // GET ADRESS

            $company = Companies::all()->first();

            $url = urlencode($company->address.",".$company->zipcode.",".$company->city);

            
            $content =
            "<iframe width='100%' height='".$height."' frameborder='0' style='border:0'
            src='https://www.google.com/maps/embed/v1/place?q=".$url."&key=".$api_key."&zoom=".$zoom."' allowfullscreen></iframe>";


            return $content;

            
        }

    }

?>