<?php

    // CRE ATE TIME DB


    $order_ref_id = Order::get_order_id();

    $booking_end = 0;

    if(!empty($delay)){ $booking_end = $timestamp + $delay; }
    if(!isset($type)){  $type = ""; }
    if(empty($booking_start)){ $booking_start = time(); } 
   // if(!isset($payment_type)){ $payment_type = ""; } 
   
   /*
   Delivery price er blevet fjernet fordi den først skal sættes efter at adressen er angivet.
      delivery_price = '".$delivery_price."', 
    if($type == "pickup"){ $delivery_price = 0; }
    */
   

 
    // ORDER
    
    $upd = [];

    if(!empty($type)){ 
      
      $upd[]= "delivery_type = '".$type."'" ; 
    
      Order::set_booking($order_ref_id,$booking_start,$booking_end,$sap);

    }

    if(!empty($payment_type)){ $upd[]= "payment_type = '".$payment_type."'"; }



    $sql = "
    update orders set 
    ".implode(",",$upd)."
    where id = ".$order_ref_id ;

    DB::update($sql);




    

	

?>