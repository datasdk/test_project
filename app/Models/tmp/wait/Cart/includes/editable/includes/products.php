<?php

    $images_in_shop = Layout::get("images_in_shop");

?>


<div class="cart-editable-wrapper">

    
    <div class='cart_title_header'>
        
        <b><?php echo $title;?></b>
        <p><?php echo Sentence::translate("Here are all the items added to the cart");?></p>
        
    </div>


<?php

    if($amount > 0){

?>

    <table class="shopping-cart-overview">

        <tr>
            
            <?php if($images_in_shop):?>
            <th class='img'></th>
            <?php endif; ?>
            
            <th class='product'></th>
            <th class='singleprice' align="right" nowrap><?php echo Sentence::translate("SINGLE PRICE");?></th>
            <!--
            <th class='discount' align="right">Rabat</th>
            -->
            <th class='totalprice' align="right"><?php echo Sentence::translate("TOTAL");?></th>
            <th class='amount'></th>
            <th class='remove'></th>
        </tr>


<?php


        if(!empty($products))
        foreach($products as $key_id => $val){
                

            $product_ref_id = $val["product_ref_id"];

            $variant_name = array();

            $variant_price = array();

            $amount = $val["amount"];

            $single_price = $val["price"];

            $discount = $val["discount"];

            $name = $val["name"];

            $description = $val["description"];

            $variant_ref_id = 0;


        

            if(isset($val["variants"]))
            foreach($val["variants"] as $category => $val2){
                    

                $variant_name[] = ucfirst($val2["specification"]);


                if($val2["variant_ref_id"]){

                    $variant_ref_id = $val2["variant_ref_id"];

                }
                

            }
                

            

            
            echo "<tr class='token_".$key_id." item-wrapper'>";
                

                if($images_in_shop){
                

                    echo "<td class='img'>";


                        if(isset($val["image"])){
                                    

                            foreach($val["image"] as $val3){

                                if(in_array($variant_ref_id,$val3["variant_ref_id"])){
                                    
                                    
                                    $img = Cloudi::format($val3["image"],50,50);


                                    if($img){

                                        echo "<img src='".$img."'  alt='".$_SERVER["HTTP_HOST"]."'>";

                                    }
                                    
                                        
                                    break;
                                        
                                }
                                        
                            }
                                
                        }


                    echo "</td>";
                        

                }



            echo "<td class='product' >";
                    
                echo "<div class='product_title'>".ucfirst($name)."</div>";
                
                echo "<div class='description'>".ucfirst($description)."</div>";


            echo "</td>";
            


            echo "<td class='singleprice' align='right' nowrap>";


                echo Price::insert($single_price - $discount);

            
            echo "</td>";

            /*
            echo "<td class='discount' align='right' nowrap>";


                echo "-".($discount * $amount)." kr.";

            
            echo "</td>";
            */

            echo "<td class='totalprice' align='right' nowrap>";


                echo Price::insert(($single_price - $discount) * $amount);

            
            echo "</td>";


                echo "<td class='amount'>";


                    echo "<div class='flex'>";


                        echo "<a 
                        href='".self::get_change_amount_url($key_id,"-1")."' 
                        class='sm-hidden'>-</a>";
                    
                        
                        echo "
                        <input type='number' 
                        name='amount' 
                        value='".$amount."' 
                        onFocus='this.select()'            
                        onChange='".self::get_change_amount_url($key_id,"this.value")."'>";
                            
                        echo "
                        <a href='".self::get_change_amount_url($key_id,"+1")."' class='sm-hidden'>+</a>";
                        
                    echo "</div>";


                echo "</td>";


                echo "
                <td class='remove'> 
                <a href='javascript:cart_remove_product(\"".$key_id."\")'>";
                
                echo "<i class='fas fa-times'></i>";

                echo "
                </a>
                </td>";


            echo "</tr>";

                

        }
            

        echo "</table>";


    }

?>

</div>