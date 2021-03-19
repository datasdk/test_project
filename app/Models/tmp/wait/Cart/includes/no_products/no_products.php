<div class='cart_overview'>
        
    <h4>

        <?php Sentence::translate("Shopping cart"); ?>

    </h4>
          

    <div class='alert alert-success block'>
                    
        <?php
            echo Sentence::translate("There are no new items for the basket");
        ?>
                    
    </div>


    <a href='<?php echo Languages::lang_url()."/"; ?>' class='btn btn-default mt-2'>
        
        <?php

            echo Sentence::translate("Click here to continue shopping");

        ?>
                                    
    </a>

</div>