
<?php

    namespace App\Models\Api\Api;


    class Link{


        public static function insert($arr = []){


            $label = "";
            $name = "";
            $link = "";
            $class = "";


            extract($arr);


            if(!empty($url) and empty($link)){ $link = $url; }


            if($link == "open_booking"){ $link = "javascript: booking_popup()"; }


            if(empty($link)){ $link = "#"; }
            if(empty($label)){ $label = $name; }
            if(empty($class)){ $class = $name; }

      
            $content = "<a href='".$link."' name='".$name."' class='".$class."'>".$label."</a>";


            return $content;


        }



        public static function anchor($name){


            $object_ref_id = Frontend::set($name,"anchor");

            return "<a name='".$name."'></a>";

        }


    }

?>