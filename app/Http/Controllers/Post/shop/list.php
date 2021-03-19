<?php

    $category_ref_id = 0;
    $rules = false;
    $variants = false;
    $page = 0;
    $order_by = 0;
    $search = false;

    $min_price = false;
    $max_price = false;



    if(isset($_POST["category_ref_id"])){ $category_ref_id = $_POST["category_ref_id"]; }
    
    if(isset($_POST["order_by"])){ $order_by = $_POST["order_by"]; }
    
    if(isset($_POST["page"])){ $page = $_POST["page"]; }
    
    if(isset($_POST["rules"])){ $rules = $_POST["rules"]; }
    
    if(isset($_POST["variants"])){ $variants = $_POST["variants"];  }
    
    if(isset($_POST["min_price"])){ $min_price = $_POST["min_price"]; } 

    if(isset($_POST["max_price"])){ $max_price = $_POST["max_price"];  }

    if(isset($_POST["search"])){ $search = preg_replace('/[^A-Za-z0-9]/', '',$_POST["search"]);  }
    

    $options = [
    "categories" => [$category_ref_id], 
    "page" => $page, 
    "order_by" => $order_by, 
    "variants" => $variants, 
    "min_price" => $min_price, 
    "max_price" => $max_price, 
    "search" => $search
    ];

    
    shop::insert($options);
 

?>