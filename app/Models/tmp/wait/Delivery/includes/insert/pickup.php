<?php


    $pickup_active = Settings::get("pickup_active");

    $pickup_sap = Settings::get("pickup_sap");

    $pickup_options_set_time = Settings::get("pickup_options_set_time");
    
    $full_address = Company::full_address();

?>


<?php

    if($pickup_active):

?>

        <div class="option-wrapper">


            <div class="option">
        

                <label>

                    <input type="radio" name="delivery_type" value="pickup" 
                    class="delivery_type" <?php echo $sel["pickup"]["type"]; ?>> 
                    
                    <?php 
                    
                        echo "<span>".Sentence::translate("Pickup at")."</span>";
                        
                        echo "<span>&#160;</span>";

                        echo "<i>".$full_address."</i>";
                    
                    ?>

                </label>
                
   
                <?php if($pickup_options_set_time): ?>


                    <div class="option-sub-menu">

                        <?php if($pickup_sap){ ?>

                            <label>
                                    
                                <input type="radio" name="pickup[option]" class="delivery_options" 
                                value="sap" <?php echo $sel["pickup"]["sap"]; ?>> <?php echo Sentence::translate("As soon as possible");?>

                            </label>


                            <label>
                                    
                                <input type="radio" name="pickup[option]" class="delivery_options" 
                                value="time" <?php echo $sel["pickup"]["time"]; ?>> <?php echo Sentence::translate("Choose a time");?>

                            </label>

                        <?php 
                        
                            }

                        ?>


                        <?php if($pickup_options_set_time): ?>

                            <div class="option_datepicker">

                                <?php

                                    $arr = ["name"=>"pickup",
                                            "type"=>"pickup",
                                            "default_timestamp"=>$sel["pickup"]["booking_start"]
                                           ];

                                    Datepicker::insert($arr);

                                ?>

                            </div>

                        <?php endif; ?>
                        
                        
                    </div>


                <?php endif; ?>

            </div>

        </div>

<?php

    endif;

?>