<?php

    
    $host = Page::get_host();

    $repay = false;

        
    $accepturl = "/";

    $declineurl = "/";

    $order_ref_id = 0;

    $subscription = 0;



    if(isset($_GET["accepturl"])){          $accepturl = urldecode($_GET["accepturl"]); }

    if(isset($_GET["declineurl"])){         $declineurl = urldecode($_GET["declineurl"]); }

    if(isset($_GET["customer_ref_id"])){    $customer_ref_id = $_GET["customer_ref_id"]; }

    if(isset($_GET["order_ref_id"])){       $order_ref_id = $_GET["order_ref_id"]; }
        
    if(isset($_GET["subscription"])){       $subscription = $_GET["subscription"]; }

    
    if(empty($customer_ref_id)){

        $customer_ref_id = Order::get_customer_by_order_id($order_ref_id);

    }


      
    $accepturl = $host."/payment/onpay/complete?accepturl=".urlencode($accepturl)."&declineurl=".urlencode($declineurl)."&order_ref_id=".$order_ref_id."&subscription=".$subscription."&customer_ref_id=".$customer_ref_id;
        
    $declineurl = $host."/payment/onpay/failed?declineurl=".urlencode($declineurl);


    include(__DIR__."/include/form.php");

            
     


?>   