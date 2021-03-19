<?php

    // set payment type

    if($payment_type){


        if(
        $payment_type == "stripe" OR
        $payment_type == "mobilepay"){

            $is_paid = 1;

        }
        

        if(
        $payment_type == "stripe" OR
        $payment_type == "mobilepay" OR
        $payment_type == "bank" OR
        $payment_type == "arrive"
        ){

            
            $sql = "
            update orders 
            set 
            payment_type = '".$payment_type."' ";


            if(isset($is_paid)){

                $sql.= ",is_paid = '".$is_paid."' ";

            }
            
            
            $sql.= "where id = '".$order_ref_id."'";

            DB::update($sql);    


        }
        

    }


    // set delivery type

    if($delivery_type){


        if(
        $delivery_type == "delivery" OR
        $delivery_type == "pickup" OR 
        $delivery_type == "booking"
        ){

            $sql = "
            update orders 
            set delivery_type = '".$delivery_type."'
            where id = '".$order_ref_id."'";

            DB::update($sql);    

        }
        

    }


    // set invoice number

    $sql = "select MAX(invoice_number) from orders where order_done = 1";

    $result = Format::current(DB::select($sql));

    $invoice_number = intval($result["MAX(invoice_number)"]) + 1;


    $sql = "update orders set invoice_number = '".$invoice_number."' where id = '".$order_ref_id."'";

    DB::update($sql);

?>