<?php

    // REUIRED FIELDS
    
    $password        = $_POST["password"];
    
    $password_repeat = $_POST["password_repeat"];

    $customer_ref_id = Customer::getCustomerId();;


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


    

    if(empty($customer_ref_id)){

        Login::log_off();

        echo json_encode(array("logged_off"=>true,"success"=>false,"title"=>Sentence::translate("You have been logged off"),"msg"=>Sentence::translate("Please log in again to change your user information")));

        exit();

    }


    if(strlen($password) < 6){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("Your password is too short"),"msg"=>Sentence::translate("Your password is too short. Please specify at least 6 characters")));

        exit();

    }


    if($password != $password_repeat){

        echo json_encode(array("success"=>false,"title"=>Sentence::translate("The passwords are not identical"),"msg"=>Sentence::translate("The specified passwords are not the same")));

        exit();

    }




    $crypt_password = Password_manager::create($password);


    $sql = "update customers set password = '".$crypt_password."' where id = '".$customer_ref_id."'";
    
    DB::update($sql);
    

    echo json_encode(array("success"=>true,"title"=>Sentence::translate("Your account is updated"),"msg"=>Sentence::translate("Your password has now been changed")));


    exit();
   

?>