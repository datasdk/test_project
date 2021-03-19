<?php


    echo '<div class="product-overview">';


    
        foreach($products as $product_id => $val){
                    
                        
            $amount = ($val["amount"]);

            $single_price = $val["price"];

            $discount = $val["discount"];


            $variant_name = array();

            $variant_price = 0;
                        
            $variant_ref_id = 0;

                        
            if(isset($val["variants"]))
            foreach($val["variants"] as $val2){
                            


                $variant_name[] = ucfirst($val2["specification"]);
                            

                if($val2["variant_ref_id"]){

                    $variant_ref_id = $val2["variant_ref_id"];

                }

            }
                
                
                
            echo '<div class="product-item">';

            
                if($images_in_shop)
                if(!empty($val["image"])){


                    echo '<div class="image">';

                
                        $img = Cloudi::format($val["image"],50,50);


                        if($img){

                            echo "<img src='".$img."'  alt='".$_SERVER["HTTP_HOST"]."'>";
                                    
                        }
                                

                    echo "</div>";
                                        
                }
                
                



                echo '<div class="name">';
                        
                    echo $amount.' x '.ucfirst($val["name"]);
                        
                    echo '<div class="description">'.ucfirst($val["description"]).'</div>';
                            
                echo '</div>';
                            
                echo '<div class="price">'.Format::number(($single_price - $discount) * $amount,1).'  kr.</div>'; 
                
            echo '</div>';
                    

        }


    echo "</div>";

?>