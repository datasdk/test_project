<?php

    class Signup{


        public static function insert($arr = []){


            $title = "";

            $formular_name = "signup";

            $firstname = false;
            $lastname = false;
            $address = false;
            $housenumber = false;
            $floor = false;
            $zipcode = false;
            $city = false;     

            $accept_url = "";


            extract($arr);



            ob_start();


                include(__DIR__."/includes/signup.php");


                $content = ob_get_contents();


            ob_end_clean();


            
            return $content;

        }


    }

?>