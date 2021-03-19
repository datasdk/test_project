<?php

    namespace App\Models\Cookies;


    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;



    class Cookies extends Model{


        public static $cookie;


        public static function get($name){

  

            if(isset($_COOKIE[$name])) {


                $value = $_COOKIE[$name];


            } 
            
            else 
            
            if(isset(self::$cookie[$name])){


                $value = self::$cookie[$name];


            } else {


                return false;


            }
            


            $value = json_decode($value);


            return $value;


        }



        public static function set($name,$value,$time = 0,$path = "/"){



            if(!$time){


                $time = time() + (86400 * 1); // 86400 = 1 day


            }
 


            $value = json_encode($value);


            setcookie($name, $value, $time, $path); 


            self::$cookie[$name] = $value;


        }



        public static function remove($name){


            unset($_COOKIE[$name]);


            $past = time() - 3600;
            

            setcookie( $name, null, $past, '/' );
            

            unset(self::$cookie[$name]);


        }



        public static function remove_all(){


            unset($_COOKIE);


            clearstatcache();


            $past = time() - 3600;

            
            if(isset($_COOKIE ))
            foreach ( $_COOKIE as $key => $value )
            {
              
                setcookie( $key, $value, $past, '/' );

            }


        }



        public static function exists($name){


            if(isset($_COOKIE[$name])) {


                return true;


            } 
            
            else 
            
            if(self::$cookie[$name]){


                return true;


            }
            

            return false;


        }


      

        public static function push($name,$value,$time = 0,$path = "/"){



            if(self::exists($name)){


                $v = self::get($name); 

                if(!is_array($v)){ $v = [$v]; }

                $v[] = $value;

                $value = $v;
                

            } else {


                if(!is_array($value)){ $value = [$value]; }

         
            }


            self::set($name, $value, $time, $path); 

        }
        

    }




?>

  