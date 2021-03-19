<?php

    $order = Order::get(1);

  


    if(empty($order["payment_type"]))
    if(empty($order["delivery_type"])){

        $lang = Languages::lang_url();

        header("location: ".$lang."/checkout/info");

        die();

    }
    
?>

