<?php

    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;


    Class Write{




        public static function msg($msg){

            $_SESSION["error_message"][] =  $msg;	

        }



        public static function log($msg,$filename = 'errors'){
            
            
            //$type = Logger::WARNING;


            $type = Logger::DEBUG;

            $domain = $_SERVER["HTTP_HOST"];


            // create a log channel
            $log = new Logger("");

            $log->pushHandler(new StreamHandler(LOGS.'/'.$domain.'/'.$filename.'.log', $type));

            // add records to the log
            $log->error($msg);

        }



    }

?>