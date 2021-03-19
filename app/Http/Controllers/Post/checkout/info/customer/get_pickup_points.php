<?php
/*
    $delivery_type = "";

    $order_ref_id = Order::get_order_id();

    $order = Order::get($order_ref_id);


    if($order){



        $delivery_type = $order["delivery_type"];

        $package_store = Shipping::is_package_store($delivery_type);
        

        if($package_store){
*/



    $order_ref_id = Order::get_order_id();


    $delivery_type  = $_POST["delivery_type"];


    $address        = $_POST["address"];
    $housenumber    = $_POST["housenumber"];
    $zipcode        = $_POST["zipcode"];
    $city           = $_POST["city"];
    $country_code   = "DK";


    $carrier_code = Shipping::get_carrier_by_code($delivery_type);

    $full_address = trim($address." ".$housenumber);
        

    Shipping::selector($full_address,$zipcode,$carrier_code,$country_code,false,$order_ref_id);



            /*
        }

    

    }
    */
    


?>