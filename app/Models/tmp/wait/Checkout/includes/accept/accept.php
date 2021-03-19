

    <?php
        
        $order_ref_id = Order::get_order_id();
        $lang = Languages::lang_url();
        
    ?>




        <form id ="form" method="post" action="/post/checkout/accept" 
        onsubmit="return checkout_accept_submit()">


            <div class="sektion">


                <div class="content">


                    <div class="sektion-title">
                        <?php echo Sentence::translate("Accept the purchase")?>
                    </div>


                    <?php

                        echo Reciept::create($order_ref_id);
                        


                        echo Terms::accept();

                    ?>


                </div>

            </div>


            <div class="navigation">
                                    
                <a href="<?php echo $lang; ?>/checkout/info" class="checkout-back-btn">
                                
                    <?php

                        echo Sentence::translate("Back");

                    ?>

                </a>
                    
                    
                <button type="submit" class="checkout-next-btn">

                    <?php

                        echo Sentence::translate("Next page");

                    ?>
                                
                </button>
                                
            </div>


        </form>




    <div class="terms_wrapper">
        <?php echo Terms::get(); ?>
    </div>

