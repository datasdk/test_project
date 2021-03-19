<?php

    $order = Order::get();

    if(empty($order["promotion_code"])){

?>


    <div class="promotion_code">
          
    
        <form method="post" onsubmit="return promotioncode_submit(this)">

            <div>
                
                <h4><?php echo Sentence::translate("Gift certificate / discount code"); ?></h4>
                
                <div >

                    <label>

                        <input type="text" name="promotion_code" value="">
                        
                        <button class="promotioncode-btn"><?php echo Sentence::translate("Redeem code");?></button>

                    </label>

                </div>

            </div>

        </form>

    </div>

<?php

    }

?>