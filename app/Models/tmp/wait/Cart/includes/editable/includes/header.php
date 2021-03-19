<?php

    $products = Order::load_products();

    $prices = Order::load_prices($order_ref_id);


    $sql = "select * from orders where id = '".$order_ref_id."'";
    $order = current(DB::select($sql));


    $vat_included = $order["vat_included"];


    $sum = floatval($prices["sum"]);

    $delivery_price = floatval($order["delivery_price"]);
    $fee            = floatval($order["fee"]);
    $discount       = floatval($order["discount"]);

    if($discount > $sum){ $discount = $sum; }

    $total = $sum + $delivery_price + $fee - $discount;


    $subtotal = Shop::validate_vat($total);



    $amount =  Cart::amount();

    $title = Sentence::translate("You have")." ";


    $str = "product";
    
    if($amount != 1){ $str .= "s"; }


    $title .= $amount . " " . Sentence::translate($str) . " ";
    
    $title .= Sentence::translate("in the cart");

    

?>