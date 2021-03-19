<?php

   
    include(__DIR__."/delivery/delivery.php");

    include(__DIR__."/customer/customer.php");

  
    echo json_encode( array("success"=>true,"url"=>$return_url) );

?>