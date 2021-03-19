<?php

    namespace App\Models\Api\Api;


    
    class Lock{


        public static function set($name){
    
            $_SESSION["system"]["lock"][$name] = true;

        }


        public static function exists($name){
          
            if(isset($_SESSION["system"]["lock"][$name])){

                return true;

            }

            return false;

        }


        public static function remove($name){
    
            if(isset($_SESSION["system"]["lock"][$name])){

                unset($_SESSION["system"]["lock"][$name]);

            }
            
        }

    }

?>