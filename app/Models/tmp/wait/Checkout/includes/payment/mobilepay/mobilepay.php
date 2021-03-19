<?php

    // MOBILEPAY REDIRECT;

    $order_ref_id = Order::get_order_id();

    
    $api_key = "2y127pr16iIAPHWQXzSSZ5Q7su3qeItufYHaKCvtke1uUZgg8o8ZSzE82";

    $api_password = "2y127pr16iIAPHWQXzSSZ5Q7su3qeItufYHaKCvtke1uUZgg8o8ZSzE82";

    $prodocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https' : 'http';


    // landing page after confirmation
    

    $callback  = false;
    $accepturl = $prodocol."://".$_SERVER['HTTP_HOST']."/checkout/complete/mobilepay/";

    
    $url = MobilePay::send($order_ref_id,$callback,$accepturl);


    header("location: ".$url);

    die();

?>