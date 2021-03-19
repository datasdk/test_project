 <!--
        INFOMATION
    -->

<div class="info_wrapper">


    <div class="info">
        
        <h4><?php echo Sentence::translate("Shopping cart"); ?></h4>

        <?php

            Cart::insert(["type"=>"checkout"]);
        
        ?>
            
    </div>

    
    <?php if(Settings::get("delivery_active")): ?>


        <div class="info">

            <h4><?php echo Sentence::translate("Delivery interval"); ?></h4>

            <?php

                Delivery::get("delivery");
            
            ?>

        </div>

    
        <div class="info">

            <h4><?php echo Sentence::translate("Delivery Price"); ?></h4>

            <?php

                echo Delivery::price();

            ?>

        </div>
    
        
    <?php endif; ?>


</div>