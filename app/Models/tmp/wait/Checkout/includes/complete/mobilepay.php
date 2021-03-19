<?php

    // THIS IS THE LANDING PAGE FOR MOBILEPAY
    
/*
    if($arg["api_key"] == "2y127pr16iIAPHWQXzSSZ5Q7su3qeItufYHaKCvtke1uUZgg8o8ZSzE82")
    if($arg["api_password"] == "$2y$12$3hYOYEbTuYCk4N1nFVRRPOh02pGrmmP2faWSd1X1FQ.BPyQwaOv9i"){



    }
*/



    $mobilepay_payment = true;

    $order_ref_id = Order::get_order_id();

    $sql = "update orders set is_paid = 1 where id = ".$order_ref_id;

    DB::update($sql);
    

    include(__DIR__."/complete.php");

    
?>