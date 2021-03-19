<?php

    $accept = true;

    // stop multible requests of this validation
    if(isset($_SESSION["validate_order_".$order_ref_id])){

        $accept = false;
        
    } 

    $_SESSION["validate_order_".$order_ref_id] = true;

    // if order is done dont access
    if($order["order_done"] != 0){
        
        $accept = false;

    }


    // if order is empty

    if(empty($order) or empty($order_ref_id)){

        $accept = false;

    }

?>