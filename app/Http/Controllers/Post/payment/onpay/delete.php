<?php


    $accepturl = $_GET["accepturl"];

        
    if(!empty($_GET["customer_ref_id"])){
           

        $customer_ref_id = $_GET["customer_ref_id"];

        Customer::remove_ccrg($customer_ref_id);

    }
      


    header("location: ".$accepturl);

    die();

?>