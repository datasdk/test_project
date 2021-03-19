<div class="calculation-wrapper">


    <div class="calculation">

        <h4>Indkøbskurv</h4>


        <?php

            Reciept::set_calculation(["type"=>"checkout"]);

            $lang = Languages::lang_url();
        
        ?>
        


        <a href='<?php echo $lang; ?>/checkout/info' class='cart-submit'> 
        <i class='fas fa-shopping-cart'></i> 
        <?php echo Sentence::translate("Checkout"); ?>
        </a>


        <?php

            Shop::insert_free_delivery();
        
        ?>

    </div>

</div>