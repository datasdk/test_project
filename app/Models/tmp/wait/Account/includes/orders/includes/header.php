<?php

    $customer_ref_id = Customer::getCustomerId();


    $sql = "select * from orders where customer_ref_id = '".$customer_ref_id."' order by id desc";


    $result = DB::select($sql);
    


?>