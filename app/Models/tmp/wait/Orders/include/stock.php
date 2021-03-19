<?php

    if(!$stock_request){

     

        Stock::pick($order_ref_id);

    }
    

    Stock::active_stock_request($order_ref_id);
    
?>