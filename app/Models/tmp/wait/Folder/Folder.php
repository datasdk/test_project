<?php

    namespace App\Models\Api\Api;

    class Folder{


        public static function create($dir){
            
            
            if (!file_exists($dir)) {
                
                

                mkdir($dir, 0777, true);

                return true;

            }
            
            return false;

        }

        public static function exists($dir){
            
            
            if (file_exists($dir)) {
            
                return true;

            }

            return false;
        
        }
        

        public static function remove($dirname) {
            

   
        if (is_dir($dirname)) {
            
            $dir = new RecursiveDirectoryIterator($dirname, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::CHILD_FIRST ) as $filename => $file) {
                if (is_file($filename))
                    unlink($filename);
                else
                    rmdir($filename);
            }
            rmdir($dirname); // Now remove myfolder
        }



        }


    }

?>