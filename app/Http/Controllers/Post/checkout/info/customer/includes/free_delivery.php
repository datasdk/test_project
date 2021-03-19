<?php

    
    $sum = Order::load_prices()["sum"];

    $delivery_free_delivery_active = Settings::get("delivery_free_delivery_active");

    $delivery_free_delivery_over_amount = Settings::get("delivery_free_delivery_over_amount");

    $free_delivery = $order["free_delivery"];

    
    // hvis gratis levering er aktiv og køb er over "gratis leverings-køb-indstillingerne"
    // eller hvis rabatkode med gratis levering er aktivt

    if(
        $delivery_type == "delivery" 
        and 
        ($delivery_free_delivery_active 
        and
        $sum >= $delivery_free_delivery_over_amount)
        or
        $free_delivery
        ){

            // tilsættes ordren efter checkout
            $_SESSION["free_delivery_discount"] = $delivery_price;

            $delivery_price = 0;
      
            
    }

?>