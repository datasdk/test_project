<?php


    $info = Company::get();




    $last_order_ref_id = Shop::get_last_order_id();

    $text_checkout_reciept = Settings::get("reciept_note");


    $url =  Languages::lang_url();



    if(empty($last_order_ref_id)){

        header("location: /");

    }


?>