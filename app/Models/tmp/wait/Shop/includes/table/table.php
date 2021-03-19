<?php

    $c = Categories::get(["categories"=>$categories]);

 

    if($c)
    foreach($c as $val){


        if(isset($val["products"])){


            $products = $val["products"];


?>

        <div class="booking-accessories">


            <form id="booking-accessories-form" method="post"  
            onsubmit="return shoplist_table_submit(this)">


                <input type="hidden" name="callback" value="<?php echo $callback; ?>">

            
                <?php

                    echo "
                    <table >
                                    
                        <tr>
                            <th ></th>
                            <th ><b>".$price_text."</b></th>
                            <th align='center' width='100'><b>".$amount_text."</b></th>
                        </tr>
                    ";

                       

                    foreach($products as $id => $val){

                    
                        $name = $val["name"];
                        $description = $val["description"];
                        $min = $val["min"];
                        $max = $val["max"];
            
                        $o = Cart::in_cart($val["id"]);


                        echo "
                        <tr>
                            <td class='accessories-item'>";
                                            
                            echo "<strong class='name'>".$name."</strong>";

                            echo "<div class='description'>".nl2br($description)."</div>";

                            echo "<input type='hidden' name='product[]' value='".$id."'>";

                        echo "</td>";


                        echo "
                        <td align='' nowrap class='price' >";
                        

                            if(!empty($val["prices"])){


                                $prices = $val["prices"]["category"];


                                foreach($prices as $category_ref_id => $val){


                                    $name = $val["name"];
                                    $variants = $val["variants"];

                                    
                                    echo "<div class='booking-product-variants'>";

                           
                                        foreach($variants as $variant_ref_id => $val2){


                                            $name = ucfirst(Sentence::get($val2["name"]));
                                                
                                            $price = current($val2["price"]);

                                            $valuta_ref_id = key($val2["price"]);

                                            $price = Price::insert($price,$valuta_ref_id);



                                            echo "<div class='booking-product-variant'>";

                                                echo "<div class='variant-field'>";

                                                    echo "<input type='radio' name='variant[".$category_ref_id."]' value='".$variant_ref_id."'>";
                                                
                                                echo "</div>";

                                            
                                                echo "<div class='product-name'><label>".$name."</label></div>";

                                                        
                                                echo "<div class='product-price'>".$price."</div>";

                                  
                                            echo "</div>";


                                        }


                                    echo "</div>";


                                }


                            } else {

                                $valuta_ref_id = key($val["price"]);
                                $price = current($val["price"])["price"];
                                
                                echo Price::insert($price,$valuta_ref_id);

                            }


                            


                        
                        echo "</td>
                        <td align='center'>";
                                            


                            
                            if($max == 1){


                                $sel = "";

                                if($o){

                                    $sel = "checked";

                                }
                                
                            
                                $label = Sentence::translate("Choose");

                                
                                echo "<label> 
                                        
                                        <input type='checkbox' name='specification[".$id."]' value='1' ".$sel."> 

                                        ".$label."
                                        
                                    </label>";

        

                            } else {
                                        
                                $min = 0;
                                $max = 100;

                                $val2 = 0;

                                if($o){

                                    $val2 = $o["amount"];

                                }


                                if($min)
                                if($val2 < $min){ $val2 = $min; }

                                if($max)
                                if($val2 > $max){ $val2 = $max; }



                                echo "<select name='specification[".$id."]'>";


                                    for($i = $min ; $i <= $max ; $i++){

                                        $sel = "";

                                        if($i == $val2){ $sel = "selected"; }

                                        echo "<option value='".$i."' ".$sel.">".$i."</option>";

                                    }


                                echo "</select>";


                              


                            }
                                            

                            echo "
                            </td>
                            </tr>
                            ";     

                        }
                                    

                    echo "</table>";

                ?>


<!--
                <button class="shop-table-submit">Næste</button>
            -->
            
            </form>
        

        </div>


 <?php
    
        }

    }
 
 ?>
