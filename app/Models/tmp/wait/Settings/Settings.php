<?php

    namespace App\Models\Settings;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    use DB;


    class Settings extends Model{
        
        use HasFactory;

        public static $settings = array();


        public static function loads(){


  

            $settings = array();

            $sql = "select * from settings_general";

            $result = DB::select($sql);


            foreach ($result as $row){

                $value = trim($row->value);

                if($value == "1" or $value == "0"){ $value = intval($value); }

                $settings[trim($row->name)] = $value;

            }

            self::$settings =  $settings;

        }


        public static function get($name = 0){

            if(empty(self::$settings)){ self::loads(); }


            if(empty($name)){

                return self::$settings;

            }

            
            if(isset(self::$settings[$name])){

                return self::$settings[$name];

            }
            
            return false;

        }

    
        public static function set($name,$value){


            $sql = "update settings_general set value = '".$value."' where name = '".$name."'";

            DB::update($sql);


        }


    }


?>