<?php


    if($payment_result)
    foreach($payment_result as $val):

        $name = $val["name"];
        $title = $val["title"];
        $image = $val["image"];

?>

    <div class="option-wrapper">


        <!--CREDIT CAR-->

        <div class="option">
            
            <label  >

                <input type="radio" name="payment_type"
                value="<?php echo $name; ?>" <?php echo $sel["payment"][$name]; ?>> 
                
                <?php 
                
                    echo Sentence::translate("Online payment with creditcard");
                    
                ?>


                <?php if($image): ?>

                    <div class="payment_types">

                        <span>

                            <img src="<?php echo $image; ?>">

                        </span>

                    </div>

                <?php endif; ?>


            </label>

        </div>

    </div>

<?php

    endforeach;

?>