<?php

    $result = Shipping::get();

    $delivery_standard_price = 
    Settings::get("delivery_standard_price");

?>



    <div class="option-wrapper">

        <?php

   
            foreach($result as $val){


                $src = "/assets/images/delivery/";

                $logo               = $src.$val["logo"];
                $code               = $val["code"];
                $name               = $val["name"];
                $carrier            = $val["carrier"];
                $description        = $val["description"];
                $calculation_type   = $val["calculation_type"];
                $sorting            = $val["sorting"];
                $active             = $val["active"];
                

                $price = 0;


                if($calculation_type == "standard_price"){

                    $price = $delivery_standard_price;

                } 

                if($calculation_type == "standard"){

                    $price = 0; // kilo pris skal ind her

                }
                
        
        ?>


            <div class="option shipping-option">

                    
                <label >
                    

                    <span class="shipping-header">


                        <input type="radio" 
                        name="delivery_type" 
                        class="delivery_type" 
                        value="<?php echo $code; ?>" 
                        <?php echo $sel[$code]["type"]; ?>> 

                        <span class='carrier'><?php echo $carrier;?></span> 
                                
                        <span class='description'><?php echo Sentence::get($description);?></span>


                    </span>

                    
                    <span class="shipping-footer">

                        <img src='<?php echo $logo;?>' class='shipping-img'>

                        <span class='price'><?php echo Price::insert($price); ?></span> 

                    </span>
                    

        
                </label>

     
            </div>


        <?php

            }
        
        ?>

    </div>

