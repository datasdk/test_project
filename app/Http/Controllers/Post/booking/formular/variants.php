<?php

    $variants = [];

    if(empty($_POST["product_ref_id"])){ exit(); }



    $product_ref_id = $_POST["product_ref_id"];

    if(isset($_POST["variants"])){ $variants = $_POST["variants"]; }



    $p = Format::current( Booking::products($product_ref_id) );


    if(empty($p)){ exit(); }



    // variants
            
    if(isset($p["prices"]["category"]))
    foreach($p["prices"]["category"] as $category_ref_id => $arr1){


        echo "<div class='variant-category-wrapper'>";

            $category_name = ucfirst( Sentence::get($arr1["name"]) );

            echo "<h4>".$category_name."</h4>";
    
            echo "<div class='variant-wrapper'>";

                
            foreach($arr1["variants"] as $variant_ref_id => $arr2){

            
                $variant_name = ucfirst( Sentence::get($arr2["name"]) );

                $valuta_ref_id = Valuta::get_default_valuta();

                $price = $arr2["price"][$valuta_ref_id];

                        
                echo "<div class='variant'>";

                    echo "<label>";
                                   
                        echo "<span class='variant_name'>";


                            echo "<input type='radio' name='variants[".$category_ref_id."]' value='".$variant_ref_id."'";

                            if($variants)
                            if(in_array($variant_ref_id,$variants)){

                                echo "checked";

                            }
                                        
                            echo ">";
                                        
                            echo $variant_name;
                                        
                            echo "</span>";


                            echo "<span class='variant_price'>";


                                if(!Price::is_free($price)){

                                    echo Price::insert($price,$valuta_ref_id);

                                }

                                                                                
                            echo "</span>";


                        echo "</label>";
                                
                                
                    echo "</div>";
        

                }


            echo "</div>";

                
        echo "</div>";


    }


?>