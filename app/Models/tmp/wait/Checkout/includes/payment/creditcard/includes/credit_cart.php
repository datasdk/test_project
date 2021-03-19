
    <h4><?php echo Sentence::translate("ONLINE PAYMENT"); ?></h4>

    <?php

        if(!$live){


            echo "<div class='alert alert-danger'>";
            
            echo Sentence::translate("This is a test version of the payment window. The money is not deducted from the card.");
            
            echo "</div>";

        }

    ?>


    <div class = "payment-wrapper">


        <div class="payment-content">


            <div class="payment_info">

                <li>   
                
                    <label><?php echo Sentence::translate("Card number");?></label>

                    <input type="number" name="cardno" value="<?php echo $cardno; ?>" 
                    placeholder = "" autocomplete='cc-number'>
                
                </li>

                <li>

                    <label><?php echo Sentence::translate("Expiration date");?></label>
           
                    <select name="expmonth" autocomplete='cc-exp-month'>
                        
                        <?php

                            for($i=1; $i <= 12 ;$i++){
                                    
                                if($i < 10){ $i = "0".$i; }

                                echo '<option value="'.$i.'">'.$i.'</option>';

                            }
                            
                        ?>

                    </select>


                    <div class="space Vcenter">/</div>


                    <select name="expyear" autocomplete='cc-exp-year'>
                        
                        <?php

                            for($i = date("Y"); $i <= date("Y") +10; $i++){
                                    
                                echo '<option value="'.$i.'">'.$i.'</option>';

                            }
                            
                        ?>

                    </select>

                </li>

  
                <li>
                    <label><?php echo Sentence::translate("Check digits");?></label>
                    <input type="number" name="cvc" value = "<?php echo $cvc; ?>"  placeholder = "" autocomplete='cc-csc'>
                    
                    <div class="space"></div>

                    <img src="/assets/images/checkout/cvc.gif">

                </li>

            
            </div>


            <div class="payment_overview">

                <div>
                    <div class="col-left"><?php echo Sentence::translate("Amount");?></div>
                    <div class="col-right"><?php echo Format::number($prices["subtotal"],1); ?> DKK</div>
                </div>


            </div>

        </div>

            
        <div class="payment-footer">

            <div class="float-left">
            <div><?php echo Sentence::translate("Date");?>:  <?php echo date("d/m Y"); ?></div>
            <div><?php echo Sentence::translate("Reference number");?>:  <?php echo $order["reference_number"];?></div> 
            </div> 
            

            <div class="payment-types">
                
                <img src="/assets/images/checkout/creditcards.gif">
      
            </div>


            <div class="company">

                <i class="fas fa-lock"></i>

                
                <?php 
                
                    echo Sentence::translate("The payment is processed securely and encrypted through our Danish acquirer Yourpay APS.");
                    
                    echo '<a href="https://www.yourpay.io/om-yourpay/" target="_blank">'.Sentence::translate("Read more").'</a>';

                ?>
                
                
            </div>


        </div>

    </div>

