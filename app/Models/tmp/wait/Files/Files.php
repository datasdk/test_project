<?php

    namespace App\Models\Api\Api;

    Class Files{


        public static function download($url,$download_name){
            

            $ext = pathinfo($url, PATHINFO_EXTENSION);

            $src = "tmp/".time()."_".uniqid() . "." . $ext;


            $contents = file_get_contents($url);

            file_put_contents($src,$contents);
    

    
            if(file_exists($src)) {
    /*
                header('Content-Type: audio/mpeg');
                header('Content-Disposition: attachment; filename="'.$download_name.'"');
                header('Content-length: '. filesize($src));
                header('Cache-Control: no-cache');
                header('Content-Transfer-Encoding: chunked'); 
                readfile($src);
                exit;
      */          
            }


        }


        public static function time($src){
            
            if(file_exists($src)){

                return filectime($src);

            }
            
            return 0;

        }


        public static function scandir($dir,$file_types = "*"){



            if($file_types != "*"){

                if(!is_array($file_types)){ $file_types = [$file_types]; }

                $file_types = array_map('strtolower',$file_types);

            }
                        
            
            
            $return = [];

            foreach (scandir($dir) as $file) {


                $ext = pathinfo($file, PATHINFO_EXTENSION);

                
                $add = false;


                if($file_types === "*"){

                    $add = true;

                } else if(in_array($ext,$file_types)){

                    $add = true;
                    
                }



                if($add){

                    $return[]= $file;

                }


            }


            return $return;

        }


        public static function is_ext($file,$ext){


            $r = pathinfo($file, PATHINFO_EXTENSION);


            if($ext == $r){

                return true;

            }

            return false;

        }



        public static function create($filedir,$content = ""){


            $dir = dirname($filedir);

            if(!file_exists($dir)){ Folder::create($dir); }


            $file = fopen( $filedir, "w");
                    
            fwrite($file, $content);
            
            fclose($file);

        }


     
        public static function remove($filedir){


            if(file_exists($filedir)){

                unlink($filedir);

                return true;

            }
            
            return false;

        }


        public static function include($filedir){

            return Page::include($filedir);

        }

        
    }

?>