<?php


    $order_ref_id = Order::get_order_id();

    $order = Order::get($order_ref_id);


    $payment_type = $order["payment_type"];
    $is_paid = $order["is_paid"];

    
    $lang_url = Languages::lang_url();


    // set terms to read
    
    $sql = "
    update orders set accepted_terms_of_trade = 1 
    where id = ".$order_ref_id ;
    
    DB::update($sql);


    
    // if payment type is mobilepay or creditcard or the order is not paid

    if($is_paid){

        $url = "/checkout/complete";  

    }

    else

    if($payment_type == "stripe"){

        $url = "/checkout/payment/creditcard"; 

    }

    else
    
    if($payment_type == "mobilepay"){ 
        
        $url = "/checkout/payment/mobilepay"; 
    
    }
    
    else

    if($payment_type == "arrive"){ 

        $url = "/checkout/complete";  
    
    } 
    
    else 
    
    if($payment_type == "bank"){

        $url = "/checkout/complete";  

    }

 
    if(isset($url)){

        header("location: ". $lang_url. $url);

        exit();

    } else {

        header("location: ".$lang_url."/checkout/accept/error");

        exit();
        
    }

?>