<?php

    class Url{


        public static function host(){

            return $_SERVER['HTTP_HOST'];

        }


        public static function prodocol(){

            $prodocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https' : 'http';

            return $prodocol."://";
            
        }

        
        public static function get(){
        

            $protocol = self::prodocol();

            $url = $protocol.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

            return $url;


        }


        public static function base(){

            $prodocol = self::prodocol();

            return $prodocol.self::host();

        }


        public static function get_domain(){

            $prodocol = self::prodocol();

            return $prodocol.self::host();

        }

    }

?>