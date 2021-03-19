<?php

    $delivery_type = ""; 
    $payment_type  = ""; 
    

    $payment_result = Payment::get();



    $sel["delivery"]["type"] = "";
    $sel["delivery"]["sap"] = "checked";
    $sel["delivery"]["time"] = "";
    $sel["delivery"]["booking_start"] = "";


    $sel["pickup"]["type"] = "";
    $sel["pickup"]["sap"] = "checked";
    $sel["pickup"]["time"] = "";
    $sel["pickup"]["booking_start"] = "";


    $sel["GLSDK_SD"]["type"] = "";
    $sel["GLSDK_HD"]["type"] = "";
    $sel["GLSDK_BP"]["type"] = "";
    $sel["PDK_PPR"]["type"] = "";
    $sel["PDK_BP"]["type"] = "";




    foreach($payment_result as $val){

        $sel["payment"][$val["name"]] = "";

    }

    
    $order_ref_id = Order::get_order_id(); 

    $order = Order::get($order_ref_id);


    // DELIVERY

    if($order){

        $delivery_type = $order["delivery_type"]; 
        $payment_type  = $order["payment_type"]; 
        $promotion_code = $order["promotion_code"]; 

    }

    
    

    
    if($delivery_type){


        $sel[$delivery_type]["type"] = "checked"; 

        
        if($order["sap"]){ 
            
            $sel[$delivery_type]["sap"] = "checked"; 
        
        }
        else{ 
            
            $sel[$delivery_type]["sap"] = ""; 
            $sel[$delivery_type]["time"] = "checked"; 
        
        }

        $sel[$delivery_type]["booking_start"] = $order["booking_start"]; 

    }

    
    // PAYMENT

    
    $sel["payment"][$payment_type] = "checked"; 
    
  
?>