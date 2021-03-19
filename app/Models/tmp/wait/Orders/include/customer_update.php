<?php

    if($customer_ref_id){

        
        
        
        $c["company"]       = ($company) ? $company : $order["company"];
        $c["cvr"]           = ($cvr) ? $cvr : $order["cvr"];
        $c["ean"]           = ($ean) ? $ean : $order["ean"];
        $c["firstname"]     = ($firstname) ? $firstname : $order["firstname"];
        $c["lastname"]      = ($lastname) ? $lastname : $order["lastname"];
        $c["address"]       = ($address) ? $address : $order["address"];
        $c["housenumber"]   = ($housenumber) ? $housenumber : $order["housenumber"];
        $c["floor"]         = ($floor) ? $floor : $order["floor"];
        $c["zipcode"]       = ($zipcode) ? $zipcode : $order["zipcode"];
        $c["city"]          = ($city) ? $city : $order["city"];
        $c["phone"]         = ($phone) ? $phone : $order["phone"];
        $c["email"]         = ($email) ? $email : $order["email"];



        
        
            // PASSWORD
            // if password is empty. Create random password and show it in the mail    

            $hide_password = true;
            
            if($password == "[RANDOM]"){

                $password = Format::strtolower(substr($c["firstname"],0,2).substr($c["lastname"],0,3).rand(100, 999));

                $hide_password = false;

            }
            
        
    
    
            $customer_exists = Customer::exists_by_email($c["email"]);
            $customer_is_logged_in = Customer::is_logged_in();


            // if customer is not logged or customer dosent exists 
            if(!$customer_exists and !$customer_is_logged_in){
        

                if($create_account){

                    $customer_ref_id = Customer::add($c["company"],$c["cvr"],$c["ean"],$c["firstname"],$c["lastname"],$c["address"],$c["housenumber"],$c["floor"],$c["zipcode"],$c["city"],$c["phone"],$c["email"],$password);
            
                    Order::add_customer_to_order($customer_ref_id,$order_ref_id);

                    // SEND WELCOME MAIL IF CUSTOMER NOT EXISTS
                    Customer::send_welcome_email($customer_ref_id,$password,$hide_password);
                
                }
            

            } else if($customer_is_logged_in){
        
                // UPDATE CUSTOMER IF CUSTOMER IS LOGGED IN
                
                // update fjernes da der kommer for mange problemer ud af det.
                // kunde info skal fremover opdateres på kundeside
        

                $arr = [
                    "customer_ref_id" => $customer_ref_id,
                    "company" => $c["company"],
                    "cvr" => $c["cvr"],
                    "firstname" => $c["firstname"],
                    "lastname" => $c["lastname"],
                    "address" => $c["address"],
                    "housenumber" => $c["housenumber"],
                    "floor" => $c["floor"],
                    "zipcode" => $c["zipcode"],
                    "city" => $c["city"],
                    "phone" => $c["phone"],
                    "email" => $c["email"]
                ];
            
                
                $c = Customer::update($arr);

                
                Order::add_customer_to_order($customer_ref_id,$order_ref_id);

                
            }
            


        // add E-mail to newsletter

        if($accept_newsletter){

            Newsletter::subscribe($c["email"]);

        }



    }

 
?>