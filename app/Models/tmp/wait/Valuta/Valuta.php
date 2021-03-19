<?php

    Class Valuta{

        
        public static $valuta;


        public static function load(){


            if(!DB::numrows("select id from valuta")){
        
                $arr = ["valuta_number"=>208,"valuta_code"=>"DKK","standard"=>1];
        
                DB::insert("valuta",$arr);
        
            }

            

            $sql = "select * from valuta order by standard desc";

            $result = DB::select($sql);


            $valuta = array();

            foreach($result as $val){

                $valuta[$val["valuta_number"]] = $val["valuta_code"];

            }
       

            self::$valuta = $valuta;

        }


        public static function get($valuta_ref_id = 0){


            if(empty(self::$valuta)){

                self::load();

            }


            $valuta = self::$valuta;


            if(isset($valuta[$valuta_ref_id])){


                return $valuta[$valuta_ref_id];


            } else {

                return current($valuta);

            }


            return false;


        }



        public static function get_default_valuta(){

            
            if(empty(self::$valuta)){

                self::load();

            }


            $valuta = self::$valuta;


            // get first valuta. the first is always standard
            return current(array_keys($valuta)); 



            return false;


        }



        public static function get_valuta_code($valuta_ref_id = 0){


            if(empty(self::$valuta)){

                self::load();

            }


            $valuta = self::$valuta;


            if(!$valuta_ref_id){

                $valuta_ref_id = self::get_default_valuta();

            }
         

            if(isset($valuta[$valuta_ref_id])){

                return $valuta[$valuta_ref_id];

            }


            return false;

        }


     


    }

?>