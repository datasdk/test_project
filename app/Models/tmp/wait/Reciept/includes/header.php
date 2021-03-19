<?php


    $customer = Order::load_customer($order_ref_id);

    $company  = Company::get();

    $second_address = Order::get_alternative_delivery_address($order_ref_id);

 


    $sql = "select * from orders where id = '".$order_ref_id."'";
    
    $order = current(DB::select($sql));



    if(!empty($company["phone"])){

        foreach($company["phone"] as $val){

            if($val["main_number"]){

                "Tlf: ".$company["phone"] = $val["number"];

                break;

            }

        } 

    }



    if(!empty($company["email"])){

        foreach($company["email"] as $val){

            if($val["transactions"]){

                $company["email"] = $val["email"];

                break;

            }

        }

    }



    if(!empty($company["cvr"])){    $company["cvr"]   = "Cvr: ".$company["cvr"]; }
  //  if(!empty($company["phone"])){  $company["phone"] = "Tlf: ".$company["phone"]; }
    
    if(!empty($customer["cvr"])){   $customer["cvr"] = "Cvr: ".$customer["cvr"]; }
   // if(!empty($customer["phone"])){ $customer["phone"] = "Tlf: ".$customer["phone"]; }


   
   if($second_address){

        $sender_title1 = "Billing address";
        
    } else {

        $sender_title1 = "Delivery address";

    }


?>

   
