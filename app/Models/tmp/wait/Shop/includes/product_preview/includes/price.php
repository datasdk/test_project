<?php

    if(isset($val["prices"])){

            
        echo '<div class="variant_wrapper">';



        foreach($val["prices"]["category"] as $category_ref_id => $val2){
            

            $name = Sentence::get($val2["name"]);

            $variant_prices = array();

            $variants = $val2["variants"];


            echo '<div class="variant_option_wrapper">';

            echo '<div class="category_name">'.ucfirst($name).'</div>';
            


            if(count($variants) == 1){



                $name  = Sentence::get(current($variants)["name"]);
                $price = current($variants)["price"];
            
                $variant_ref_id = key($variants);

                $variant_prices[$variant_ref_id] = $price;


                echo '
                <label>

                <input 
                type="checkbox" 
                name="variants['.$category_ref_id.']" 
                value="'.$variant_ref_id.'" 
                onchange="change_variant()" 
                class="variants"> '.ucfirst($name).'

                </label>
                ';


            } else {

            
        

                echo '<select name="variants['.$category_ref_id.']" onchange="change_variant()" class="variants">';


                    foreach($variants as $variant_ref_id => $val3){


                        $name = Sentence::get(ucfirst($val3["name"]));
                        $url = Shop::url_encode($val3["name"]);
                        $price = $val3["price"];
                        
                        $variant_prices[$variant_ref_id] = $price;


                        $sel = "";

                        if(in_array($variant_ref_id,$selected_variants)){ $sel = "selected"; }

                        
                        echo '
                        <option value="'.$variant_ref_id.'" 
                        data-url="'.$url.'" '.$sel.'>'.ucfirst($name).'</option>';
                        


                        if($price){

                            echo "<span class='price'>".(Format::number($price))."</span>";

                        }
                    
                    }
                
                
                echo "</select>";


            }
            
            


            foreach($variant_prices as $variant_ref_id => $price){


                $price = $price[$valuta_ref_id];

                echo "<input type='hidden' value='".$price."' class='variant_price_".$variant_ref_id."' readonly>";


            }


            echo '</div>';

        }


        echo "</div>";


    }

?>