<?php

    // ADD CUSTOMER ID TO ORDER
    $sql = "
    update orders 
    set 

    formular_ref_id = '".$formular_ref_id."',
    order_done = '1',
    is_new = '1',
    date = '".time()."',
    stock_request = '".$stock_request."'

    where id = '".$order_ref_id."'";


    DB::update($sql);


   // update products

   $sql = "
   update order_products 
   set 
   locked = 1,
   complete = 1,
   valuta = 'dkk'

   where 
   order_ref_id = '".$order_ref_id."' ";

   DB::update($sql);





    Shop::set_last_order_id($order_ref_id);
    

    Order::close();


    /**
     * UPDATE JSON
     * Opdate json order in the admin section
     * 
    */
    

    $prodocol = Page::getProdocol();
    $host = Page::getHost();


    
    $sql = "select * from orders where order_done = 1";

    $order_amount = DB::numrows($sql);


    
    $domain = $prodocol.'admin.'.$host;

    if($_SERVER['REMOTE_ADDR'] == "127.0.0.1"){ $domain = $prodocol.'local.admin.datas.dk'; }


    

    Session::remove("checkout_customer_new_password");


   // Json::set('orders.json',array("order_amount"=>$order_amount),$domain);


?>