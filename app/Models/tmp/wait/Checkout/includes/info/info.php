<?php

    if($form){

        echo "<form method='post' onsubmit='return checkout_form_submit(this)'>";

    }



        if($has_payment){

            include(__DIR__."/payment/payment.php");

        }


        if($has_delivery){

            Delivery::insert();

        }
            
        
        if($customer){

            include(__DIR__."/customer/customer.php");

        }

        
        
        if($has_navigation){

            Checkout::navigation($return_url);

        }

        
       // Shipping::insert();


    if($form){

        echo "</form>";

    }

?>