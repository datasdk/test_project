<?php
    
    namespace App\Models\Api\Api;
    
    use \Curl\Curl;


    Class Request{

    
        public static function set($url,$params = array(),$type="post",$format = "array"){

            
            $curl = new Curl();

            if($type == "post"){ $curl->post($url, $params); }
            
            if($type == "get"){  $curl->get($url, $params); }
        
         

            if ($curl->error) {
                
                return 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
        
            } else {
                

                $res = $curl->response;

        
                
                //or $format == "json" den laver bøvl

                if($format == "array"){

                
                    if(gettype($res) == "array"){

                        return $res;

                    } else {

                        return json_decode($res,true);

                    }


                } 
                
                else 
                
                if($format == "json"){


                    if(gettype($res) == "array"){

                        // måske fejl?
                        return json_encode($res);

                    } else {

                        return $res;

                    }

                    

                }



                return $res;
            
        
            }
        
            $curl->close();



        }

    }

?>