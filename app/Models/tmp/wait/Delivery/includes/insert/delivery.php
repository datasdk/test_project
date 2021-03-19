<?php

    $delivery_active = Settings::get("delivery_active");

    $delivery_sap = Settings::get("delivery_sap");

    $delivery_options_set_time = Settings::get("delivery_options_set_time");

    



    if($delivery_active):

?>

    <div class="option-wrapper">


        <div class="option">


            <label >

                <input type="radio" name="delivery_type" class="delivery_type" 
                value="delivery" <?php echo $sel["delivery"]["type"]; ?>> 
                <?php echo Sentence::translate("Delivery to the address");?>


            </label>



            <?php if($delivery_options_set_time): ?>
                

                <div class="option-sub-menu">

                    <?php if($delivery_sap){ ?>

                        <label>
                            
                            <input type="radio" name="delivery[option]" class="delivery_options" 
                            value="sap" <?php echo $sel["delivery"]["sap"]; ?>> <?php echo Sentence::translate("As soon as possible");?>

                        </label>

                
                        <label>
                            
                            <input type="radio" name="delivery[option]" class="delivery_options" 
                            value="time" <?php echo $sel["delivery"]["time"]; ?>> <?php echo Sentence::translate("Choose a time");?>

                        </label>


                        <?php

                            } 
                            
                        ?>



                    <?php

                        if($delivery_options_set_time):
                    
                    ?>

                        <div class="option_datepicker">

                            <?php

                                $arr = ["name"=>"delivery",
                                        "type"=>"delivery",
                                        "default_timestamp"=>$sel["delivery"]["booking_start"]
                                        ];

                                Datepicker::insert($arr);

                            ?>

                        </div>

                    <?php

                        endif;
                    
                    ?>

                </div>


            <?php

                endif;
                    
            ?>


        </div>


    </div>


<?php

    endif;

?>