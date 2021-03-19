<?php
    
/*

LAV CART::CHANGE()


    if(isset($_POST["token"]))
    if(isset($_POST["amount"]))
    if(isset($_POST["relative"])){


        $order_ref_id = Order::get_order_id();
        
        $token      = $_POST["token"];
        $amount     = $_POST["amount"];
        $relative   = $_POST["relative"];
        $sku        = $token;


        $title  = Sentence::translate("Out of stock");

        $msg    = Sentence::translate("The stated quantity of this item is not in stock");;



        // check stock

        $in_stock = Stock::in_stock_by_sku($order_ref_id,$sku,$amount,$relative);


        if(!$in_stock){

            echo json_encode( array("success"=>false,"title"=>$title,"msg"=>$msg,"single_amount"=>$amount,"total_amount"=>$amount) );

            die();

        }


        $sql = "update order_products set ";


        if($relative){

            $sql .= "amount = amount + ".$amount ." ";

        } else {

            $sql .= "amount = ".$amount." ";

        }
        

        $sql .= " 
        where 
        key_id = '".$token."' and 
        order_ref_id = ".$order_ref_id." and 
        complete = 0";
        
        $result = DB::update($sql);
        

        // REMOVE ZERO

        $sql = "delete from order_products where amount <= 0 and order_ref_id = ".$order_ref_id;

        $result = DB::delete($sql);


        // CALCULATE

        $sql = "select amount,key_id from order_products where order_ref_id = ".$order_ref_id;

        $result = DB::select($sql);


        $single_amount = 0;
        $total_amount = 0;

        foreach($result as $val){

            if($token == $val["key_id"]){

                $single_amount += $val["amount"];

            }

            $total_amount += $val["amount"];

        }


        Promotion::update();


        echo json_encode( array("success"=>true,"single_amount"=>$single_amount,"total_amount"=>$total_amount) );


    }

*/

?>