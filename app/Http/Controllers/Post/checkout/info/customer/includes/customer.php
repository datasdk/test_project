<?php

    if(isset($_POST["password"])){


        $password = $_POST["password"];

        Session::set("checkout_customer_new_password",$password);

    }


    Session::set("checkout_customer_create_account",0);

    
    if(isset($_POST["create_account"])){

        Session::set("checkout_customer_create_account",1);

    }



?>