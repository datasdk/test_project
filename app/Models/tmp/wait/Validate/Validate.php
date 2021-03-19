<?php

    Class Validate{



        public static function has_illegal_characters($str){


            if(preg_match("/[A-Za-z0-9]+/", $str) != TRUE){

                return true;

            }


            return false;

        }

    }

?>
