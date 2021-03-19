<?php


    // REUIRED FIELDS
    
    $email      = $_POST["email"];
    
    $password   = "";

    $customer_ref_id = Customer::getCustomerId();;

    $customer = Customer::get($customer_ref_id);


    // EXTRA FIELDS


    $organization = "";
    $cvr = "";
    $ean = "";
    $firstname = "";
    $lastname =  "";
    $address = "";
    $housenumber = "";
    $floor = "";
    $city =  "";
    $zipcode = "";
    $phone =  "";
    $comment = "";
    
    

    if(isset($_POST["token"])){      $token = $_POST["token"]; }
    
    if(isset($_POST["organization"])){    $organization  = $_POST["organization"]; }

    if(isset($_POST["cvr"])){        $cvr  = $_POST["cvr"]; }

    if(isset($_POST["ean"])){        $ean  = $_POST["ean"]; }

    if(isset($_POST["firstname"])){  $firstname = $_POST["firstname"]; }
    
    if(isset($_POST["lastname"])){   $lastname = $_POST["lastname"]; }
    
    if(isset($_POST["address"])){    $address = $_POST["address"]; }

    if(isset($_POST["housenumber"])){    $housenumber = $_POST["housenumber"]; }

    if(isset($_POST["floor"])){      $floor = $_POST["floor"]; }

    if(isset($_POST["city"])){       $city = $_POST["city"]; }

    if(isset($_POST["zipcode"])){    $zipcode = $_POST["zipcode"]; }

    if(isset($_POST["phone"])){      $phone = $_POST["phone"]; }

    if(isset($_POST["comment"])){    $comment = $_POST["comment"]; }
    


    if(isset($_POST["image"])){     $image = $_POST["image"]; }



    
    if(empty($customer_ref_id)){

        Login::log_off();

        echo json_encode(array("logged_off"=>true,"success"=>false,"title"=>Sentence::translate("You have been logged af"),"msg"=>Sentence::translate("Please log in again to change your user information")));

        exit();

    }


    if(empty($customer)){

        Login::log_off();

        echo json_encode(array("logged_off"=>true,"success"=>false,"title"=>Sentence::translate("Technical error"),"msg"=>Sentence::translate("You cannot change your information as your user may have been removed")));

        exit();

    }



    // CHANGE EMAIL

    if($customer){


        $old_email = $customer["email"];
   
        if($old_email != $email)
        if(Customer::exists_by_email($email))
        {
        
            echo json_encode(array("success"=>false,"title"=>Sentence::translate("This email is taken"),"msg"=>Sentence::translate("Your email is occupied by another user. Please select another email")));
        
            exit();
        
        }

    }


    if(!Email::validate($email)){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("Email is invalid"),"msg"=>Sentence::translate("Your email address is incorrect")));

        exit();

    }

    if(!empty($image)){

        Cloudi::upload(["file"=>$image]);

    }
    

    $arr = [
        "customer_ref_id" => $customer_ref_id,
        "company" => $organization,
        "cvr" => $cvr,
        "firstname" => $firstname,
        "lastname" => $lastname,
        "address" => $address,
        "housenumber" => $housenumber,
        "floor" => $floor,
        "zipcode" => $zipcode,
        "city" => $city,
        "phone" => $phone,
        "email" => $email,
        "password" => $password,
        "comment" => $comment
    ];

    
    $c = Customer::update($arr);



    if($c){


         echo json_encode(array("success"=>true,"title"=>Sentence::translate("The account is updated"),"msg"=>Sentence::translate("Your account is now updated")));


    } else {


        echo json_encode(array("success"=>false,"title"=>Sentence::translate("Updating failed"),"msg"=>Sentence::translate("Your account was not updated. The error may be due to the email being used by another user")));


    }
    
    
    exit();
   

?>