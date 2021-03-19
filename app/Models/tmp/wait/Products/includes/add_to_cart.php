<?php


    $close_after_closing_time = Settings::get("close_webshop_after_closing_time");

    $enable_shop = Settings::get("enable_shop");     


    // 

    if(!$enable_shop){

        return ["success"=>false,"title"=>Sentence::translate("The shop is not available"),"msg"=>Sentence::translate("You cannot place an order as the shop is not available")];

    }



    // IF SHOP IS CLOSED AND YOU CANT ORDER AFTER CLOSING TIME

    if(!OpeningHours::is_open())
    if($close_after_closing_time){

        return ["success"=>false,"title"=>Sentence::translate("We are closed"),"msg"=>Sentence::translate("You cannot place an order when we have closed")];


    }


    $total_amount = 0;


    // variants

   
    


    // set product token

    $product_token = sha1($product_ref_id.implode("_",$variants));

    $order_ref_id = Order::get_order_id();



    if(!$order_ref_id){

        $order_ref_id = Order::create();

    } 


    // BOOKING
/*

find en løsning på hvad man skal gøre hvis det er biler der sælges og mad

    if(Booking::has_booking($product_ref_id)){

        return json_encode( array("success"=>true,"open_booking_popup"=>true) );
    }

*/
    

    // IF no variants is given, and the variants i required.
    // send back json request, an open up the custom-product-popup

    if(Layout::get("shoplist") == "menu")
    if(Products::has_variants($product_ref_id) and empty($variants)){

        
        return  ["success"=>true,"open_custom_product_popup"=>true];

    }

   

    $result = Order::add_product($order_ref_id,$product_ref_id,$amount,$variants);
    

    if(isset($result["success"]))
    if($result["success"] == false){

        return $result;

    }



    $total_amount = 0;

    $sql = "
    SELECT 
    SUM(amount) as amount
    FROM order_products 
    where order_ref_id = '".$order_ref_id."'
    GROUP BY order_ref_id";


    $val = Format::current( DB::select($sql) );

 

    $total_amount = $val["amount"];


    if(isset($arr)){

       Pocket::insert("product::add_to_cart",$arr); 

    }
    


    return ["success"=>true,"amount"=>$total_amount];

  
?>