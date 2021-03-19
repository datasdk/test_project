<?php

    namespace App\Models\Api\Api;

    Class reCaptsha{

        
        public static $site_key;

        public static $secret_key;


        public static function load(){


            $sql = "select * from frontend_recaptcha limit 1";
            
            $resut = DB::select($sql);


            if($resut){
                
                $arr = current($resut);

                self::$site_key   = $arr["site_key"];
                
                self::$secret_key = $arr["secret_key"];

            }


        }


        public static function insert(){


            if(empty(self::$site_key)){

                self::load();

            }

            
            $site_key = self::$site_key;
                
            $secret_key = self::$secret_key;


            return "<div id='g-recaptcha' class='g-recaptcha' data-sitekey='".$site_key."'></div>";


        }

        public static function validate($post){
            

            if(empty(self::$site_key)){

                self::load();

            }


            $site_key = self::$site_key;
                
            $secret_key = self::$secret_key;

        


            if(isset($post["g-recaptcha-response"])){

                $response = $post["g-recaptcha-response"];

            } else {

                $response = $post;

            }

            
            if(empty($response)){

                return false;

            }
            

            $arr = array("secret"=>$secret_key,"response"=>$response);


            $result = Request::set("https://www.google.com/recaptcha/api/siteverify",$arr,"post","json");

            
    
            if($result->success){

                return true;

            }


            return false;


        }

    }

?>