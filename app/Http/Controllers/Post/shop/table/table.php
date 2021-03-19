<?php


    $arr = Order::get_booking();


    

    $booking_start = $arr["booking_start"];

    $booking_end   = $arr["booking_end"];


    $total_days = Format::dateDiffTs($booking_start,$booking_end);
 



    // specification



    if(isset($_POST["specification"])){


        $product = $_POST["product"];

        $specification = $_POST["specification"];


        foreach($product as $product_ref_id){


            $amount = 0;

            if(isset($specification[$product_ref_id])){

                $amount = $specification[$product_ref_id];

            }
            

            if($amount > 0){


                if(!Order::product_exists(0,$product_ref_id)){
          
                    $r = Order::add_product(0,$product_ref_id,$amount);

                    json_encode($r);
                    
                } else {
            
                    $r = Order::update_product(0,$product_ref_id,$amount);

                    json_encode($r);

                }


            } else {

                $r = Order::remove_product(0,$product_ref_id);

                json_encode($r);

            }
            
            
            
        }

    }


/*
    if($product_ref_id == 35){

        $amount = $total_days;

    }
  */ 
?>
    