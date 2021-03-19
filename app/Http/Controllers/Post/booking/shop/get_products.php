<?php


    $type  = 0;

    $same = false;

    $booking_start = time();

    $booking_end = strtotime("midnight + 1 day - 1 second");




    if(isset($_POST["type"])){ $type = $_POST["type"];}
    
    $category_ref_id    = $_POST["category_ref_id"];



    if(isset($_POST["booking_start"])){

       $booking_start      = intval($_POST["booking_start"]); 

    }


    if(isset($_POST["booking_end"])){

        $booking_end        = intval($_POST["booking_end"]);

    }



    // PRODUCTS
    

    $p = Products::get(["categories"=>$category_ref_id,"rules"=>["showcase"]]);

    
    $no_products = true;


    foreach($p as $id => $val){


        if(!empty($type))
        if(!in_array($type,$val["categories"])){ continue; }

        
        $options = [
            "product_ref_id"=>$id,
            "booking_start"=>$booking_start,
            "booking_end"=>$booking_end
        ];

        
        if(Booking::is_available($options)){

            
            $no_products = false;


            $link = "javascript:booking_choose_product(".$id.")";


            $image_ref_id = 0;

            

            echo '<div class="booking-product">';
        
        
                echo '<div class="booking-product-images">';


                    echo '<div class="booking-main-img">';


                        if(isset($val["images"]))
                        foreach($val["images"] as $image_ref_id => $src){


                            $src = Cloudi::size($src,1000);
                 

                            echo '<a href = "'.$src.'" class = "main_img main_img_'.$image_ref_id.'">';

                                echo '<img src="'.$src.'" >';

                            echo '</a>';

                        }


                    echo '</div>';



                    if(isset($val["images"]))
                    if(count($val["images"]) > 1){


                        echo '<div class="booking-thumbs">';

                        

                            foreach($val["images"] as $image_ref_id => $src){

               
                                $thumb_src = Cloudi::size($src,60,60);

                                $main_src = Cloudi::size($src,1000);


                                echo '<a href = "'.$main_src.'">';

                                    echo '
                                    <img src= "'.$thumb_src.'" class="thumb" 
                                    data-image_ref_id="'.$image_ref_id.'" >';

                                echo '</a>';

                            }


                        echo '</div>';

                    }
              

              
                echo '</div>';
                

            echo '<div class="booking-product-description">';


                echo '<h2 class="booking_title">'.$val["name"].'</h2>';

                echo '<div class="booking_price">'.Price::insert($val["price"]);

                echo '<span class="booking_price_ext">'.Price::price_ext($val["price_type"]).'</span>';

                echo '</div>';

                echo '<p>'.$val["description"].'</p>';               
                    

                $txt = Sentence::translate("Choose");
                
                echo '<a href= "'.$link.'" class="booking-product-submit" value="'.$id.'"> '.$txt.'</a>';

                echo '</div>';


            echo '</div>';


        }

        
        

    }


    if($no_products){

      
        echo "<div class='alert alert-info'>";
        
        echo Sentence::translate("There are no available during this period");
        
        echo "<b> (".date("d/m Y",$booking_start);
        

        if(!$same){

            echo " - ".date("d/m Y",$booking_end);

        }

        echo ")</b>";
        

        echo "</div>";


    }

    

?>