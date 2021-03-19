<?php

    $images_in_shop = Layout::get("images_in_shop");



    if($header){

        echo "<div class='cart_title_header'>";
        
        echo "<b>".$title."</b>";

        echo "<p>".Sentence::translate("Here are all the items added to the cart")."</p>";
            
        echo "</div>";

    }

    

 
        if($booking)
        if($booking_start or $booking_start){


            $title = Sentence::translate("Booking");


            echo "<div class='cart-booking'>";


                echo "<div class='cart-booking-title'><strong>".$title."</strong></div>";

                
                echo "<div class='cart-booking-time'>";
               
                
                    echo "<strong>".date("d/m/y H:i",$booking_start)."</strong>";
               

                    if($booking_end){

                        echo " - ";
                        
                        echo "<strong>".date("d/m/y H:i",$booking_end)."</strong>"; 

                    }


                echo "</div>";

            echo "</div>";


        }


    ?>

<?php


    if(!empty($products))
    foreach($products as $token => $val){
            

        $product_ref_id = $val["product_ref_id"];

        $variant_name = array();

        $variant_price = array();

        $amount = $val["amount"];

        $single_price = $val["price"];

        $discount = $val["discount"];

        $name = $val["name"];

        $image = $val["image"];

        $description = $val["description"];

        $variant_ref_id = 0;



        if(isset($val["variants"]))
        foreach($val["variants"] as $category => $val2){
                

            $variant_name[] = ucfirst($val2["specification"]);


            if($val2["variant_ref_id"]){

                $variant_ref_id = $val2["variant_ref_id"];

            }
             

        }
            

        

        echo "<div class='product-line'>";    




            if($images_in_shop)
            if(!empty($image)){   
            
                $image = Cloudi::size($image,60,60);

                echo "<img src='".$image."'>";
        
            }



            echo "<div class='product_title'>";
            
                echo $amount." x ".ucfirst($name);

                if(!empty($variant_name)){

                    echo "<div class='description'>".ucfirst($description)."</div>";
    
                }
            
            echo "</div>";
                
                
            echo "<div class='price'>";

                echo Price::insert(($single_price - $discount) * $amount);

            echo "</div>";


/*
            echo "<div class='empty'>";

                echo '<i class="fas fa-times-circle"></i>';

            echo "</div>";
*/

        echo "</div>";




        

    }
        



?>

