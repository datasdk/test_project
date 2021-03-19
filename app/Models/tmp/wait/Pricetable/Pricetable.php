<?php

    namespace App\Models\Api\Api;

    class Pricetable {

         
        public static function get($products = []){


            ob_start();


            $p = Products::get($products);

          

            
            include(__DIR__."/includes/table.php");


            $content = ob_get_contents();
            

            ob_end_clean();


            return $content;


        }


        public static function convert_to_symbol($str){

            $str = str_replace("(tick)","<i class='fas fa-check'></i>",$str);

            return $str;

        }


    }