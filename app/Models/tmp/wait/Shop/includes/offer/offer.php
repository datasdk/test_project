
<div class="offer-wrapper">

    <?php

        $cols = [];


        foreach($result as $arr){


            foreach($arr["products"] as $product_ref_id => $val){
                


                $name           = Sentence::get($val["name"]);
                $description    = Sentence::get($val["description"]);

                $item_number    = $val["item_number"];
                $discount       = $val["discount"];
                $price          = $val["price"];

                
                $specifications = Products::specifications($product_ref_id);

                
                $valuta_ref_id  = Valuta::get_default_valuta();

                $valuta         = Valuta::get_valuta_code($valuta_ref_id);


                $priceARR   = $val["price"][$valuta_ref_id];

                $price      = $priceARR["price"];

                $type       = $priceARR["type"];
                

                

                

                $images = false;

                if(isset($val["images"])){

                    $images = current($val["images"]);

                }



                $before_price = $price;
                $save = $price / 100 * $discount;
                $price = round( $price - $save );

                if($before_price < 0){ $before_price = 0; }


                ob_start();

    ?>

            

                <div class="offer">


                    <div class="offer-header">

                        <?php if($discount): ?>
                        <div class="discount"><?php echo $discount;?>%</div>
                        <?php endif; ?>

                        <?php

                            if($images){

                                echo '<img src = "'.Cloudi::get($images).'">';

                            }
                        
                        ?>
                        
                        

                        <h1 class="offer-name"><?php echo $name; ?></h1>

                        <div class="offer-price">
                            
                            <?php 
                            
                                echo Format::number($price,true,false);
                                
                                echo " ";

                                echo $valuta;
         
                            ?>
                        
                        </div>
                  

                        <?php

                            $type = Price::price_ext($type); 
                            
                            if($type){ 
                                    
                                echo "<div class='price_type'>".$type."</div>"; 
                                
                            }

                        ?>
                      
                        <?php if($save): ?>
                        <h3 class="before">
                        <?php 
                            
                            //echo "Spar ".Format::number($save,true,false) . " " . $valuta; 
                            
                            echo "Før ".Format::number($before_price,true,false). " " . $valuta;

                        ?>
                        
                        </h3>
                        <?php endif; ?>


                        <?php 
                            
                            if(!empty($description)){
                                
                                echo '<div class="offer-description">';

                                echo $description;
                                
                                echo '</div>';

                            }
                        
                        ?>


                    </div>


                    

                   

                    


                    <div class="offer-specifications">
                        
                        <?php

              
                            if($specifications)
                            foreach($specifications as $val){


                                $category       = $val["category"];

                                $specification  = $val["specification"];


                                if($category){ $category.=": "; }



                                if(empty($category) and empty($specification)){ continue; }



                                echo '<div class="offer-item">';
                                

                                    echo '<i class="fas fa-check"></i>';
                                    

                                    if(!empty($category)){

                                        echo $category;

                                        echo ":";

                                    }
                                    
                                    
                                    echo $specification;
                                
                                    
                                echo '</div>';


                            }
                        
                        ?>

                        

                    </div>


                </div>
            

    <?php

            $col[] = ob_get_contents();

            ob_end_clean();


        }


       }


       $amount = count($col);

       if($amount > 3){ $amount = 3; }

       $op = [
            "md"=>$amount,
            "lg"=>$amount,
            "xl"=>$amount,
            "fw"=>1,
        ];

       echo Col::insert("offers",$col,$op);
    

    ?>

</div>