<?php

  

    $sql = "select id from order_booking where order_ref_id = '".$order_ref_id."'";

    // IF ORDER BOOKING INSENT DEFINED
    if(!DB::numrows($sql)){


        $arr = array("order_ref_id"=>$order_ref_id,
                     "booking_start"=>time()
                    );
        
        // DEFINE BOOKING FROM NOW
        DB::insert("order_booking",$arr);

    }

?>