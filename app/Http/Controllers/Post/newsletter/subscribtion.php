<?php

    $type  = $_POST["type"];

    $email = $_POST["email"];
    
    $customer_ref_id = 0;


    $company = "";
    $cvr = "";
    $firstname = "";
    $lastname = "";
    $address = "";
    $housenumber = "";
    $floor = "";
    $zipcode = "";
    $city = "";
    $phone = "";



    if(!Email::validate($email)){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("Error"),"msg"=>Sentence::translate("Your email is not specified correctly")));
    
        exit();

    }
         
        
  

    if($type == "subscribe"){


        // IF  CUSTOMER IS SIGNED UP
        
        if(Newsletter::is_subscribed($email)){

            echo json_encode(array("success"=>false,"title"=>Sentence::translate("Already signed up"),"msg"=>Sentence::translate("Your E-mail is already subscribed to our newsletter")));
                
            exit();

        }
        

        Newsletter::subscribe($email);


        echo json_encode(array("success"=>true,"title"=>Sentence::translate("Newsletter signed up"),"msg"=>Sentence::translate("Your e-mail is now subscribed to our newsletter")));

        exit();

                
    } 

     
    
    if($type == "unsubscribe"){

            
        Newsletter::unsubscribe($email);


        echo json_encode(array("success"=>true,"title"=>Sentence::translate("Newsletter has been canceled"),"msg"=>Sentence::translate("Your e-mail is now unsubscribed from our newsletter")));
            
        exit();

    }

    

?>