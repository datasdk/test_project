<?php



    $order = Order::get();

    $order_ref_id = Order::get_order_id();    

    $accept = true;
    

    include(__DIR__."/includes/security.php");



    if($accept){


        $accept_newsletter = Session::get("checkout_customer_accept_newsletter");

        $create_account = Session::get("checkout_customer_create_account");

        $password = Session::get("checkout_customer_new_password");
     


        $arr =  array("accept_newsletter"=>$accept_newsletter,
                      "create_account"=>$create_account,
                      "password"=>$password
                    );



        Order::complete( $arr );


     
        

        Session::remove("checkout_customer_new_password");
    
        Session::remove("checkout_customer_create_account");

        Session::remove("checkout_customer_accept_newsletter");
   
        
   }




    $lang  = Languages::lang_url();

    header("location: ".$lang."/checkout/reciept");
        
    exit;

?>