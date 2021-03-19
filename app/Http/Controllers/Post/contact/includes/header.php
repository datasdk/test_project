<?php



    $reCaptsha = reCaptsha::validate($_POST);


    if(isset($_POST["comment"]))
    if(strlen($_POST["comment"]) < 10){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("The message is too short"),"msg"=>Sentence::translate("Your message is too short. Please enter more than 10 characters")));

        exit();

    }

    if(!$reCaptsha){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("reCaptsha is invalid"),"msg"=>Sentence::translate("Please confirm that you are not a robot")));

        exit();

    }



    if(!isset($_POST["token"])){

        echo json_encode(["success"=>false]);

        die();

    }

    $formular_ref_id = $_POST["formular_ref_id"];
    $token  = $_POST["token"]; 

    $content = array();

    $specifications = array();



    $fields = array();

  
    $sql = "select * from frontend_formular where id = '".$formular_ref_id."'";

    $form = Format::current( DB::select($sql) );

    
    $sql = "select * from frontend_formular_fields where formular_ref_id = '".$formular_ref_id."'";

    $result = DB::select($sql);


    if(empty($form) or empty($result)){ 

        echo json_encode(array("success"=>false));

        die();

    }


    foreach($result  as $val){

        $fields[$val["name"]] = ["type" => $val["type"], "value" => $val["value"] ];

    }





    if(isset($fields["first_name"])){
        
        $info["first_name"] = $_POST["first_name"]; 
        
    }
    

    if(isset($fields["last_name"])){

        $info["last_name"] = $_POST["last_name"];

    }


    if(isset($fields["address"])){

        $info["address"] = $_POST["address"]; 

    }


    if(isset($fields["city"])){

        $info["city"] = $_POST["city"]; 
        $info["zipcode"] = $_POST["zipcode"]; 

    }


    if(isset($fields["phone"])){

        $info["phone"] = $_POST["phone"]; 

    }


    if(isset($fields["email"])){

        $info["email"] = $_POST["email"]; 

    }


    if(isset($fields["comment"])){

        $info["comment"] = nl2br($_POST["comment"]); 

    }


/*
    if($form["newsletter_email"]){

        $info["newsletter_email"] = $_POST["newsletter_email"]; 

    }


    if($form["newsletter_sms"]){

        $info["newsletter_sms"] = $_POST["newsletter_sms"]; 

    }
*/

    

    /**
    * SPECIFICATION
    */


    if(isset($_POST["specification"])){


        $specifications = array();


        
        foreach($_POST["specification"] as $field_id => $val){


            $name = ucfirst($fields[$field_id]["name"]);

            $type = $fields[$field_id]["type"];
            
            $value = array();



            if($type == "checkbox"){

                
                foreach($val as $key){


                    $value = explode(",",$fields[$field_id]["value"])[$key];

                    $specifications[$field_id]["name"] = $name;
                    $specifications[$field_id]["value"][] = ucfirst($value);


                }
                
            } 
        
            else if($type == "radio"){
            

                $value = explode(",",$fields[$field_id]["value"])[$val];

                $specifications[$field_id] = array("name"=>$name,"value"=>ucfirst($value));


            } else {


                $value = $val;

                $specifications[$field_id] = array("name"=>$name,"value"=>ucfirst($value));

            }

        
        }


    }    

    /**
     * EMAIL TEXT
     */



    

    if(empty($form["reciept_message"])){ 

        $e_title = Sentence::translate("Thank you for your inquiry"); 

    }


    if(empty($form["reciept_message"])){ 

        $e_mesage = Sentence::translate("We have received your inquiry and will return as soon as possible.
        <br> Should you have any questions, you are always very welcome to contact us.");

    }


    
?>