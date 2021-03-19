<?php

    $all_specifications = array();
    $s = Products::specifications();


    $categories = [];
    

    foreach($s as $val){ 

        foreach($val as $val2){ 


            if(!empty($val2["specification"])){

                $categories[]= $val2["category"];

            }
            

        }

    }

 
    $categories = array_count_values($categories);
    
   

?>

<div class="price-table-wrapper">


    <div class="price-table">


        <div class="price-header">

            <div class="price-specifications-name">

            0

            </div>

            <?php
                    
                

                foreach($p as $product_ref_id => $val){

                
                    
            ?>

                <div class="price-product-header">

                    <h4 class="price-title"><?php echo $val["name"]; ?><h4>
                    <p class="price-description">Det er den bedste i verdenen</p>

                </div>


            <?php

                }
            
            ?>

        </div>



        <div class="price-body">

        
            
            <ul class="specifications-name">

                <?php
                                            
                    foreach($categories as $c => $v){ 
                                
                        echo "<li>".$c."</li>";   

                    }

                ?>                

            </ul>

        

            <div class='specifications-values'>

                <?php

                                
                    foreach($p as $val){

                        $product_ref_id = $val["id"];

                        echo "<ul>";

                            if(isset($s[$product_ref_id]))
                            foreach($s[$product_ref_id] as $val2){
                                    
                                echo "<li>".Pricetable::convert_to_symbol($val2["specification"])."</li>";   

                            }
                            
                        echo "</ul>"; 

                    }                 

                ?>
            
            </div>


        </div>


    </div>


</div>