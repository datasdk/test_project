<?php


    $default_timestamp = $_POST["default_timestamp"];

    $min_timestamp = $_POST["min_timestamp"];

    $name = $_POST["name"];

    $type = $_POST["type"];

    $product_ref_id = $_POST["product_ref_id"];

    $persons = $_POST["persons"];

    $time_type = $_POST["time_type"];
    
    $onchange = $_POST["onchange"];

   


    $arr = ["default_timestamp"=>$default_timestamp,
            "onchange"=>$onchange,
            "min_timestamp"=>$min_timestamp,
            "name"=>$name,
            "type"=>$type,
            "product_ref_id"=>$product_ref_id,
            "persons"=>$persons,
            "type"=>$type
            ];


    $arr = Timepicker::insert($arr);



?>