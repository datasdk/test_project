<?php

    class Shopmenu{


        public static function filter($root_category = "*"){

            
            $has_filter = true;


            ob_start();


            include(__DIR__."/includes/filter.php");


            $content =  ob_get_contents();

            ob_end_clean();


            return $content;

        }


        public static function category_list($root_category = "*"){


            ob_start();
            

            include(__DIR__."/includes/category_list.php");


            $content =  ob_get_contents();

            ob_end_clean();


            return $content;
            
        }

    }

?>