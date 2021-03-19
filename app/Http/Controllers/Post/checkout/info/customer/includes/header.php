<?php


    $customer_ref_id = Customer::getCustomerId();

    $order_ref_id = Order::get_order_id();

    $order = Order::get($order_ref_id);

    $delivery_type = $order["delivery_type"];


    $lang = Languages::lang_url();

    $return_url = $lang . "/checkout/accept";


    $msg = "";
    
    $another_delivery_address = intval(isset($_POST["another_delivery_address"]));

    $email = $_POST["email"];

    $phone = $_POST["phone"];

    $comment = $_POST["comment"];


    

    $customer_require_housenumber_floor = 
    Settings::get("customer_require_housenumber_floor");


    $delivery_price = 0;



    
    $success = true;
    
    if(empty($_POST["firstname"])){                    $msg = Sentence::translate("First name is invalid your first name"); $success = false; }
    else if(empty($_POST["lastname"])){                $msg = Sentence::translate("Please insert your last name"); $success = false; }
    else if(empty($email)){                                     $msg = Sentence::translate("Please insert your phone number"); $success = false; }
    else if(empty($phone)){                                     $msg = Sentence::translate("Please insert your email"); $success = false; }
    else
    
    if(isset($_POST["address"])){
    
            
        if(empty($_POST["address"])){                  $msg = Sentence::translate("Please insert your address"); $success = false; }
    
    
        if($customer_require_housenumber_floor){
            
            if(empty($_POST["housenumber"])){          $msg = Sentence::translate("Please insert your house number"); $success = false; }
            
            
        }
        
        
        if(empty($_POST["zipcode"])){                  $msg = Sentence::translate("Please insert your zip code"); $success = false; }
        
        if(empty($_POST["city"])){                     $msg = Sentence::translate("Please insert your city"); $success = false; }
        
    
    }
        

    // ALTERNATIVE ADDRESS


    if($another_delivery_address){

        
        if(empty($_POST["billing_firstname"])){                $msg = Sentence::translate("Please insert your first name"); $success = false; }
        else if(empty($_POST["billing_lastname"])){            $msg = Sentence::translate("Please insert your last name"); $success = false; }
        else
        
        if(isset($_POST["billing_address"])){
        
                
            if(empty($_POST["billing_address"])){              $msg = Sentence::translate("Please insert your address"); $success = false; }
        
        
            if($customer_require_housenumber_floor){
                
                if(empty($_POST["billing_housenumber"])){      $msg = Sentence::translate("Please insert your house number"); $success = false; }
               
                
            }
                
            if(empty($_POST["billing_zipcode"])){              $msg = Sentence::translate("Please insert your zip code"); $success = false; }
            
            if(empty($_POST["billing_city"])){                 $msg = Sentence::translate("Please insert your city"); $success = false; }
            
        
        }


    }

    

    if(Shipping::is_package_store($delivery_type)){


        if(empty($_POST["shipping_point"])){

            $msg = Sentence::translate("Please select pickup point for delivery"); 
            
            $success = false;

        }

    }
    

    // PHONE
    
    if(strlen($phone) < 8){       
            
        $msg = Sentence::translate("Your phone number is not entered correctly");
            
        $success = false; 
        
    }
    
    
    // EMAIL
    
    if(!Email::validate($email)){
    
        $msg = Sentence::translate("Your email address is not set correctly");
            
        $success = false; 
    
    }
    
    
   
    // PASSWORD
    

    if(isset($_POST["create_account"])){
    
        $password = $_POST["password"];
        
        if(strlen($password) < 6){        
         
            $msg = Sentence::translate("Your password must be over 5 characters");
            
            $success = false; 
            
        }
    
    }


    if(!$success){

        echo json_encode(array("error"=>true,"msg"=>$msg));

        die();

    }
   

?>