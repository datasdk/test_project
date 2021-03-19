<?php

    namespace App\Models\Api\Api;

    class Pocket {
        

        public static $includes = [];

        public static $call_history = [];
            

        public static function insert($name,$params = []){


            $for_admins = 0; // dont change


            $result = self::create($name,$for_admins);


            if(!$result){ return false; }



            $code = urldecode($result["code"]);
        
            $is_modified = $result["is_modified"]; 

            $pocket_ref_id = $result["id"]; 



            $result = self::eval($name,$code,$params,$is_modified);        



            $sql = "update pocket set is_modified = '0' where id = '".$pocket_ref_id."'";
        
            DB::update($sql);



            return true;


        }


        public static function create($name,$for_admins = 0){

            
            $sql = "
            select * from pocket 

            where 
            name = '".$name."' and
            active = 1
            limit 1
            ";


            $result =  Format::current( DB::select($sql) );

 

            if(empty($result)){


                $arr = ["name"=>$name,
                        "admin"=>$for_admins,
                        "active"=>1
                        ];

                DB::insert("pocket",$arr);
                

                return false;


           } 

            return $result;


        }
        


        public static function eval($filename,$code,$params,$is_modified = 0){

            
            if(in_array($filename,self::$call_history)){

                return false;

            }

            
            if(!is_array($params)){ $params = [$params]; }

            extract($params);


            $d = Page::directories();

            $dir = $d["pocket"];
            $filedir = $dir."/".$filename.".php";


            Folder::create($dir);

            
           if($is_modified or !file_exists($filedir)){

                Files::create($filedir,$code);

           }

     
            
            include($filedir);

    
            
            self::$call_history[] = $filename;

            
        }


    }

?>