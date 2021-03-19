
<?php


    namespace App\Models\Api\Api;

    Class History{


        public static $history;



        public static function set($url = 0){


            if(!$url){

                $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            }


            $history = self::get();

            if(!is_array($history)){ return false; }


            $last_val = end($history);

            if($last_val == $url){ return $history; }

            $history[]= $url;


            Session::set("history",$history);


            return $history;

        }


        public static function get(){


            $history = Session::get("history");


            if(empty($history)){ 
                
                $history = array();
               
                Session::set("history",$history); 

            }

            return $history;


        }


        public static function remove(){

            Session::remove("history"); 

        }


        public static function back_url(){


            $history = self::get();

            if(empty($history) or !is_array($history)){ return "/"; }

            $last_val = array_slice($history, -2, count($history), true);;

            $last_val = current($last_val);

            return $last_val;


        }


        public static function remove_last_url(){


            $history = self::get();

            if(empty($history) or !is_array($history)){ return "/"; }

            array_pop($history);

            
            Session::set("history",$history); 


            return $history;

        }


    }

?>
