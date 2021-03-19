
<?php

    namespace App\Models\Api\Api;

    class Html{


        public static function insert($arr = []){


            if(empty($object_ref_id)){ return false;}


            $params = Frontend::get_parameter($object_ref_id);

            
            extract($arr);
        

            


            $html = $params["html"];
            $type = $params["type"];


            $html = urldecode($html);


            echo self::eval($html);  
   


        }


        public static function tagSplit($code){


            $arr = explode(" ",$code);

            $i = 0;

            $r = [];

            $type = "html";


            foreach($arr as $val){


                $val = trim($val);

                
                if($val == "<?php"){ 

                    $type = "php";
                    $i++; 

                }
                


                if(!isset($r[$i])){ 
                
                    $r[$i]["content"] = ""; 

                }

                
                $r[$i]["type"] = $type;
                $r[$i]["content"] .= ($val." ");


                if($val == "?>"){ 
                    $i++; 
                    $type = "html";
                }

                
            }


            return $r;

        }



        public static function eval($filedir,$code = 0){
                     

            Files::create($filedir,$code);

            return $filedir;
                 
           
        }


       


    }

?>