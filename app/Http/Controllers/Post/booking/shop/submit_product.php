<?php

    
    $product_ref_id  = $_POST["product_ref_id"];

    $booking_start  = $_POST["booking_start"];
    
    $booking_end    = $_POST["booking_end"];


    $total_days = Format::dateDiffTs($booking_start,$booking_end);


    $order_ref_id = Order::create(["delivery_type"=>"booking"]);

    Order::remove_all_products($order_ref_id);



    Order::add_product($order_ref_id,$product_ref_id,$total_days);

    Order::set_booking($order_ref_id,$booking_start,$booking_end,0);

    Order::set_delivery($order_ref_id,"booking");
    


    $pro = Products::get( ["products"=>$product_ref_id] );

    $p = Format::current( $pro );


    if($p){

        $url = $p["url"];

    }
  



    Pocket::insert("booking-submit-product-after");



    echo json_encode( [ "success" => true , "url" => $url] );


?>