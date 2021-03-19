<?php


    $product_ref_id = 0;
    
    if(isset($_POST["product_ref_id"])){

        $product_ref_id = $_POST["product_ref_id"];

    }
    

    $variants = [];

    if(isset($_POST["variants"])){

        $variants = $_POST["variants"];

    }
    


    $arr = ["product_ref_id" => $product_ref_id,
            "variants" => $variants,
            ];


    Booking::insert($arr);


?>