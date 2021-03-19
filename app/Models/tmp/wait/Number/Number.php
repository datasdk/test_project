
<?php

    namespace App\Models\Api\Api;


    class Number{

        public static function format($val,$dblzero = false){

            $number =  number_format(floatval($val), 2, ',', '.');
            
            if(!$dblzero)
            $number = str_replace(",00",",-",$number); 
            
            return $number;

        }


        public static function dblZero($val){

            return number_format($val, 2);

        }

    }

?>