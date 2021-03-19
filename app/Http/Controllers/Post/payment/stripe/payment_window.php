<?php

    $param = [];

    if(!empty($_POST)){ $param  = $_POST; }


    echo Stripe::insert( $param );

    
?>
