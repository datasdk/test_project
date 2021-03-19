<?php


    $media_ref_id = $_POST["media_ref_id"];

    $customer_ref_id = $_POST["customer_ref_id"];

    $stars = $_POST["stars"];


    $arr = [
        "media_ref_id"=>$media_ref_id,
        "customer_ref_id"=>$customer_ref_id,
        "stars"=>$stars
    ];

    Media_interaktion::set($arr);

?>