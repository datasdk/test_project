<?php

    $product_ref_id = $_POST["product_ref_id"];

    $options = ["product_ref_id" => $product_ref_id, "related_products" => false];


    Shop::product_preview($options);

?>