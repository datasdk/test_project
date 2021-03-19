
<?php

    namespace App\Models\Api\Api;


    class Routes{


        public static $app;
        public static $added_routes = [];
        public static $basedir = __DIR__."/../App/Views/";

        
        public static function start($app){

            self::$app = $app;
           
            self::scan_routes('../App/Routes/*');

        }


        public static function scan_routes($path){


            $webfolder = DB::$site_name;

            $app = self::$app;
             
            
            $scan = glob($path);
            

            foreach($scan as $item){


                if(is_dir($item)){
                    
                    $result[basename($item)] = self::scan_routes($item."/*");
                
                }
                else 
                {
                    
                    $extension = explode(".", $item);

                    
                    if(end($extension) == "php")
                    if(file_exists($item)){
                        
                
                        include( $item );

                        
                    }
                    

                }

                    
            }


            


        }



    }

   
?>