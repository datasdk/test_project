<?php

    class Str{


        public static function contains($str,$contains){


            if (strpos($str, $contains) !== false) {
                
                return true;

            }

            return false;

        }


    }

?>