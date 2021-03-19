<?php

    $params = [];

    
    $url  = "";

    $dataType = "json";

    $method = "post";


    if(isset($_POST["params"])){    $params     = $_POST["params"]; }
    
    if(isset($_POST["url"])){       $url        = $_POST["url"]; }

    if(isset($_POST["method"])){    $method     = $_POST["method"]; }

    if(isset($_POST["dataType"])){  $dataType   = $_POST["dataType"]; }




    // convert formdata to parameters
    if(is_string($params)){ 
        
        $params = str_replace(",","&",$params);

        parse_str($params,$params); 
    
    }
  
  
    if(isset($params["token"])){

        $token = $params["token"];

        Api::set_token($token);

    }
    




    $r = Api::request($url,$params,$method,$dataType);
        


    if($dataType == "array"){

                
        if(gettype($r) == "array"){

            echo $r;
            
        } else {
            
            // convert output to array
            echo json_decode($r,1);                    

        }


    } 
    
    else

    if($dataType == "json"){


        if(gettype($r) == "array"){

            echo json_encode($r);
            
        } else {
            
            // convert output to array
            echo $r;                    

        }


    }

    else
    
    {

        echo $r;

    }

    

?>