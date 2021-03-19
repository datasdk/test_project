
<?php

    
    namespace App\Models\Api\Api;


    class Layout {


        public static $layout = array();


        public static function load(){


            $mysqli = DB::mysqli();

            $layout = array();


            $sql = "select * from  layout";

            $result = mysqli_query($mysqli,$sql);
            while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){

                $value = trim($row["value"]);

                if($value == "1" or $value == "0"){ $value = intval($value); }

                $layout[trim($row["name"])] = $value;

            }

            
            self::$layout =  $layout;

        }


        public static function get($name = 0){

            if(empty(self::$layout)){ self::load(); }


            if(empty($name)){

                return self::$layout;

            }

            
            if(isset(self::$layout[$name])){

                return self::$layout[$name];

            }
            
            return false;

        }

    
        public static function set($name,$value){


            $sql = "update  layout set value = '".$value."' where name = '".$name."'";

            DB::update($sql);


        }


    }


?>