<?php

    namespace App\Models\Api\Api;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;


    Class Api extends Model{


        use HasFactory;


        public static $api_key;
        public static $secret;
    
        public static $local_url  = "http://local.api.datas.dk";
        public static $public_url = "https://api-central.dk";
        


        public static function login($api_key = 0,$secret = 0){

                     


            if(!$api_key and !$secret){


                $sql = "select * from api limit 1";
            
                $r = Format::current(DB::select($sql));
           
    
                if($r){
    
                    $api_key = $r["api_key"];
                    $secret  = $r["secret"];
    
                }

            }


            if(!$api_key or !$secret){

                return false;

            }

            self::$api_key = $api_key;
            self::$secret  = $secret;


    
            $token = self::get_token();

 

            if($token){

                return $token;

            }


            $d = self::get_domain();
     
            $p = ["api_key"=>$api_key,"secret"=>$secret,"type"=>"api"];

            $r = Request::set($d."/db/connect",$p,"post","array");

       

            if(isset($r["token"])){


                $token = $r["token"];

                
                self::set_token($token);


                return $token;


            } else {
                
                echo $r["msg"];

                self::remove_token();
                
                return false;


            }

        }
        

        public static function get_domain(){

            if(DB::is_localhost()){

                return self::$local_url;

            } else {

                return self::$public_url;

            }

            
        }


        public static function login_off(){

            self::remove_token();

        }


        public static function set_token($token){
            

            $api_key = self::$api_key;
            $secret  = self::$secret;

            $id = sha1($api_key."_".$secret);
           
        
            
            if(empty($id)){ return false; }

            
          

            $_SESSION["api_token"][$id] = $token;


        }


        public static function remove_token(){


            $api_key = self::$api_key;
            $secret = self::$secret;

            $id = sha1($api_key."_".$secret);

            
            if(empty($id)){ return false; }

            unset($_SESSION["api_token"][$id]);

        }

        

        public static function get_token(){



            $api_key = self::$api_key;
            $secret = self::$secret;


            $id = sha1($api_key."_".$secret);
            
           

            if(empty($id)){ return false; }

      
            if(!isset($_SESSION["api_token"][$id])){

                return false;

            }


            $token = $_SESSION["api_token"][$id];


            if($token){

                return $token;

            }
            
            return false;

        }
        
        
     


        public static function request($url,$params = array(),$type="post",$format = "array",$add_domain = true){



            $token = self::get_token();

            
            if(!$params){ $params = []; }
        

            if(!is_array($params)){

                return json_encode(["success"=>false,"msg"=>"param is not a array"]);

            }
            

            /*
            if(empty($token)){

                return json_encode(["success"=>false,"msg"=>"No token found"]);

            }
            */


            if($add_domain){


                $d = self::get_domain();

                if($url[0] != "/"){ $url = "/".$url; }
                
                $url = $d . $url;


            }


            
            $params["token"] = $token;
            $params["host"]  = $_SERVER["HTTP_HOST"];

    
   
            $r = Request::set($url,$params,$type,$format);
    
        
            
            if($format == "array"){

                
                if(gettype($r) == "array"){

                    return $r;
                    
                } else {
                    
                    // convert output to array
                    return json_decode($r,1);                    

                }


            } 
            
            if($format == "json"){


                if(gettype($r) == "array"){


                    return json_encode($r);
                    

                } else {
                    
                    // convert output to array
                    return $r;                    

                }


            }
            
            else
            
            {
                
                if($format == "html"){

                    $r = trim($r);

                }

                return $r;

            }

  

        }


        public static function html($url,$params = array(),$type = "post",$add_domain = true){

            return self::request($url,$params,$type,"html",$add_domain);

        }


        public static function token($api_key,$secret){

            return sha1( $api_key."_".$secret );

        }


        
    }


?>