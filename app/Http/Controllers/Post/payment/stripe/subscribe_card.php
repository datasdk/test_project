<?php


    $pi = $_POST["pi"];


    $options = ["pi"=>$pi];

    $p = Stripe::save_card($options);
    


?>

