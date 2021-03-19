<?php

    namespace App\Models\Cookiebot\Cookiebot;

    
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    Class Cookiebot extends Model{

        
        public static $cbid;


        public static function load(){


            if(!empty(self::$cbid)){

                return self::$cbid;

            }


            $sql = "select cbid from cookiebot limit 1";

            $res = DB::select($sql);


            if($res){

                $res = Format::current($res);

                self::$cbid = $res["cbid"];

            }


            return false;

        }



        public static function set_asset(){


            if(empty(self::$cbid)){

                $cbid = self::load();

            }

            if(!$cbid){ return false; }


            $url = "https://consent.cookiebot.com/uc.js";

            echo '<script id="Cookiebot" src="'.$url.'" data-cbid="'.$cbid.'" type="text/javascript" ></script>';   
 
            
        }


    }

?>