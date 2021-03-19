<?php

    $p = Stripe::create_payment_intent($_POST); 

    echo json_encode($p);

?>