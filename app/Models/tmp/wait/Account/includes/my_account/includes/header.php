<?php
    
    $customer_ref_id = Customer::getCustomerId();



    if($customer_ref_id){

        $customer = Customer::get($customer_ref_id) ;


        if($customer){

            $company     = $customer["company"];
            $cvr         = $customer["cvr"];

            $firstname   = $customer["firstname"];
            $lastname    = $customer["lastname"];
            $address     = $customer["address"];
            $housenumber = $customer["housenumber"];
            $floor       = $customer["floor"];
            $zipcode     = $customer["zipcode"];
            $city        = $customer["city"];
            $phone       = $customer["phone"];
            $email       = $customer["email"];

        }


    }
    
    


?>