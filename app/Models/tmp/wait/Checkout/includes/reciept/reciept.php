<?php

    include(__DIR__."/includes/header.php");

?>




    <!--
        CONTENT
    -->

    <div class="content_wrapper">


        <div class="content">


            <div class="sektion">    


                <h3><?php echo Sentence::translate("Your order has now been received"); ?></h3>


                <?php

                    $t = Ucfirst(Sentence::get($text_checkout_reciept));

                    if(!empty($t)){ 
                        
                        echo "<div class='order-information'>".$t."</div>"; 
                    
                    }

                ?>

                    

                <?php

                    Pdf::invoice($last_order_ref_id);
                    
                    echo Reciept::create($last_order_ref_id);
                
                ?>


            </div>
            

            <div class="">

                <a href="<?php echo $url; ?>/" class="btn checkout-return-button">
                <?php echo Sentence::translate("Back to the front page"); ?>
                </a>
                    
            </div>


        </div>


    </div>


