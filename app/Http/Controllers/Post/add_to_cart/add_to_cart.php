<?php

    if(empty($_POST)){ die("parameter_missing"); }

 

    $json = Products::add_to_cart($_POST);


    echo $json;
  
?>