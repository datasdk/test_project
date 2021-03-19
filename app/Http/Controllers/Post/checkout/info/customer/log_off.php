<?php

    if(Customer::is_logged_in()){


        $order_ref_id = Order::get_order_id();

        Login::log_off();


        if($order_ref_id){

            $sql = "
            update orders
            set 

            company = '',
            cvr = '',
            ean = '',
            firstname = '',
            lastname = '',
            address = '',
            housenumber = '',
            floor = '',
            zipcode = '',
            city = '',
            email = '',
            phone = ''
            
            where 
            order_done = 0
            and 
            id = '".$order_ref_id."'
            ";

            DB::update($sql);

        }


    }

    

?>