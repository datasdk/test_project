
<?php

    namespace App\Models\Api\Api;


    class Modified{
            

        public static function get($name){


            $sql = "select modified from modified where name = '".$name."'";


            $val = Format::current(DB::select($sql));

            
            if(empty($val["modified"])){

                return self::update($name);

            }


            return $val["modified"]; 


        }


  
        public static function update($name,$time = 0){

            
            if(!$time){ $time = time(); }


            $sql = "select id from modified where name = '".$name."'";


            if(DB::numrows($sql)){              


                $arr = [
                    "name"=>$name,
                    "modified"=>$time
                ];

                DB::insert("modified",$arr);


            } else {


                $sql = "
                update modified 
                set 
                modified = '".$time."' and 
                name = '".$name."'";

                DB::update($sql);


            }


            return $time;


        }


    }


?>