<?php


    // VALIDATE CUSTOMER


    $customer_exists = false;
    

    $sql = "select * from customers where email = '".$email."' limit 1";

    $result = DB::select($sql);




    if(count($result)){
        
        if($customer_ref_id != key($result)){

            $customer_exists = true;
      
            $password = current($result)["password"];

        }
        
       
    }
    


    // IF CUSTOMER IS NOT LOGGED IND AND EMAIL EXISTS IN THE SYSTEM

    if($customer_exists)
    if(!Customer::is_logged_in()){


        // IF CUSTOMERS PASSWORD IS EMPTY - THE CUSTOMER CAN PASS THROUGH the formular 
        // even if the customer is registered

        if(!empty($password)){
            
            $msg = Sentence::translate("This email is already registered, please log in with your user"); 

            $success = false; 

        }    

    }


?>