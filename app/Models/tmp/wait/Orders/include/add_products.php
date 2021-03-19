<?php       


    if(!$amount){ return json_encode( array("success"=>false) ); }


    $products = Products::get(["products" => [$product_ref_id] ]);




    $language_ref_id = Language::get_default_language();


    if(!isset($products[$product_ref_id])){

        return json_encode( array("success"=>false) );

    }


    $obj = $products[$product_ref_id];

    $item_number = $obj["item_number"];

    $category_ref_id = $obj["category_ref_id"];


    $name = $obj["name"];

    $description = $obj["description"];

    $date = $obj["date"];

    $min = $obj["min"];

    $max = $obj["max"];



    $discount = Discount::get($product_ref_id);



    $sku = Stock::get_sku_key($product_ref_id,$variants);


    if(!$sku){

        return json_encode( array("success"=>false) );;

    }


    $sql = "select amount from order_products 
    where order_ref_id = ".$order_ref_id." and key_id = '".$sku."'";

    $result = DB::select($sql);

    $numrows = DB::numrows($sql);

    $order_amount = intval(current($result)["amount"]);






    // MIN MAX Products

    if(!$numrows)
    if($min)
    if($amount < $min){

        $amount = $min;

    }   



    //sa($amount > $max);





    if($max)
    if($order_amount + $amount > $max){
    //  echo $order_amount." + ".$amount." > ".$max."<br>";

        $title = Sentence::translate("Limited");

        $msg = Sentence::translate("You can only buy a limited amount of this product");
        

        $msg .= " (".$max." ";

        
        if($max == 1){

            $msg .= Sentence::translate("product");

        } else {

            $msg .= Sentence::translate("products");

        }


        $msg .= ")";



        return json_encode( array("success"=>false,"title"=>$title,"msg"=>$msg) );


    }

    // in stock



    $in_stock = Stock::in_stock_by_sku($order_ref_id,$sku,$amount);



    if(!$in_stock){


        $title = Sentence::translate("Out of stock");


        if($amount > 1){

            $msg = Sentence::translate("The stated quantity of this item is not in stock");;

        } else {

            $msg = Sentence::translate("This item or variant is not in stock.");

        }
        

        return json_encode( array("success"=>false,"title"=>$title,"msg"=>$msg) );

    }



    // UPDATE


    if($numrows > 0){



        $sql = "
        update order_products set 
        
        amount = amount + ".intval($amount)."

        where 
        order_ref_id = '".$order_ref_id."' and 
        key_id = '".$sku."' and 
        complete = 0";
        
        DB::update($sql);


        return json_encode( array("success"=>true) ); ;


    }



    // INSERT

    $stock_ref_id = Stock::get_stock_id($product_ref_id,$variants);


    $q1 = array("key_id"=>$sku,
                "order_ref_id"=>$order_ref_id,
                "stock_ref_id"=>$stock_ref_id,
                "item_number"=>$item_number,
                "product_ref_id"=>$product_ref_id,
                "name"=>$name,
                "timestamp"=>time(),
                "amount"=>$amount,
                "active"=>1
                ); 

        $order_product_ref_id = 
        DB::insert("order_products",$q1);
        


        
        // variants

        $all_variants = array();

        $total_price = 0;

        $valuta_ref_id = Valuta::get_default_valuta();


        if(isset($obj["price"])){ 

            // SINGLE PRICE
            
            $total_price = $obj["price"][$valuta_ref_id]["price"]; 
        
        
        }

        else

        if(isset($obj["prices"]["category"]))
        foreach($obj["prices"]["category"] as $category_ref_id => $val){

            // MULTIBLE PRICE

            $category = Sentence::get($val["name"]);
            
            

            foreach($variants as $specification_ref_id){


                if(empty($val["variants"][$specification_ref_id])){ continue; }


                $val2    = $val["variants"][$specification_ref_id];

                $specification = Sentence::get($val2["name"]);

                $price = $val2["price"][$valuta_ref_id];
                
                $all_variants[]= $specification;

                

                // add price to total price

                    $total_price += $price;


                // insert to db

                $q2 = array("order_ref_id"=>$order_ref_id,
                            "order_product_ref_id"=>$order_product_ref_id,
                            "category"=>$category,
                            "specification"=>$specification,
                            "price"=>$price
                            ); 
                


                DB::insert("order_product_specifikations",$q2);  



            }

            
        }


        
                
    // calculate discount

    $discount_price = 0;

    if($discount){

        $discount_price = ($total_price / 100 * $discount);

    }



    $description = "";

    if($all_variants){

        $description = implode(", ",$all_variants);

    }

    // insert in db

    $sql = "
    update order_products 
    set 
    description = '".$description."', 
    price = '".$total_price."', 
    discount = '".$discount_price."'
    where id = ".$order_product_ref_id;

    DB::update($sql);


    return json_encode( array("success"=>true) );;


?>