<?php

    $total_amount = 0;

    $order_ref_id = Order::get_order_id();

    
    if($order_ref_id){

        
        $sql = "select amount from order_products where order_ref_id = '".$order_ref_id."'";

        $result = DB::select($sql);
    
    
        foreach($result as $val){
    
            $total_amount += $val["amount"];
    
        }

    }



    $class = "";

    if($fixed){

        $class = "fixed";

    }


    if($total_amount == 0){

        $class.= " hide";

    }


    echo "<div class='cart-wrapper ".$class."'>";


        $default_lang = Language::get_code_by_language_id();


        $lang = "";
        

        if(Language::$language_amount > 1){

            $lang .= "/".$default_lang;

        }   


        if(!$url){ $url .= "/cart"; }
        
        
        $url = $lang . $url;



        echo "<a href='".$url."' class='Vcenter'>";

            echo "<i class='fas fa-shopping-basket'></i>";
            
            echo "<div class='shopcart_amount Vcenter'>".$total_amount."</div>";

        echo "</a>";


    echo "</div>";

?>