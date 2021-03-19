
<?php


    namespace App\Models\Api\Api;

    Class Json{


        public static function set($name,$content,$domain = false){
            

            $name = str_replace(".json","",$name);

         

            if($domain){


                $content = http_build_query($content);

                echo Request::set($domain."/api/json/set/".$name."/".$content);


            } else {

                
                $content = json_encode($content);

                file_put_contents("assets/json/".$name.".json",$content);


            }

            

        }


        public static function get($name,$domain = false){


            $name = str_replace(".json","",$name);

            
            if($domain){


                return Request::set($domain."/api/json/get/".$name);


            } else {
                

                $url = ROOT."/assets/json/".$name.".json";

                return file_get_contents($url);


            }


        }

    }

?>