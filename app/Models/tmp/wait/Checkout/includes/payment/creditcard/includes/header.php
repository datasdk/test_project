<?php

 
    $order = Order::get();
    $prices = Order::load_prices();



    $sql = "select * from settings_payment where id = 1 and active = 1";

    $result = DB::select($sql);

    if($result){

       $live = current($result)["live"];

    }



    $cardno = "";
    $cvc = "";

    
    if(!$live){

        $cardno = "4111111111111111";
        $cvc = "123";

    }

?>