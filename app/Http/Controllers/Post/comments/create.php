<?php
    
    
    $comment_ref_id = $_POST["comment_ref_id"];

    $comment = $_POST["comment"];

    $customer_ref_id = Customer::getCustomerId();
    
    
    $comment = ucfirst($comment);



    Comments::post($comment_ref_id,$customer_ref_id,$comment);


?>