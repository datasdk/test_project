<?php

    include(__DIR__."/includes/header.php");

?>


<div class="content_wrapper">


    <div class="content">


        <div class="sektion">
                    

            <?php

                $lang_url = Languages::lang_url();

                $return_url = $lang_url."/checkout/reciept";

                echo Stripe::insert(["return_url" => $return_url]);
           
            ?>


        </div>

        <?php
                
            $lang = Languages::lang_url();

            $url = $lang."/checkout/accept";

            Checkout::navigation($url,false);

            
        ?>
            
    </div>


</div>

