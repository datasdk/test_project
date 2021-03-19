<div class="payment_wrapper">

    <div class="form-group">

        <label><?php echo Sentence::translate("Card number");?></label>

        <input type="text" name="cardno" value="" placeholder = "" autocomplete='cc-number' class="form-control">

    </div>


    <div class="form-group expiration_date">


        <label><?php echo Sentence::translate("Expiration date");?></label>


        <div class="row">

            <div class="col">
                            
                <select name="expmonth" autocomplete='cc-exp-month' class="form-control">
                                
                    <?php

                        for($i=1; $i <= 12 ;$i++){
                                            
                            if($i < 10){ $i = "0".$i; }

                            echo '<option value="'.$i.'">'.$i.'</option>';

                        }
                                    
                    ?>

                </select>
                        
            </div>

            <div class="col slash">
                /
            </div>


            <div class="col">

                <select name="expyear" autocomplete='cc-exp-year' class="form-control">
                                
                    <?php

                        for($i = date("Y"); $i <= date("Y") +10; $i++){
                                            
                            echo '<option value="'.$i.'">'.$i.'</option>';

                        }
                                    
                    ?>

                </select>

            </div>


        </div>

    </div>


       
    <div class="form-group">
        
        
        <label><?php echo Sentence::translate("Check digits");?></label>
                    
        
        <div class="row">
                                    
            <div class="col">

                <input type="text" name="cvc" value = ""  placeholder = "" autocomplete='cc-csc' class="form-control">
                        
            </div>
                        
            <div class="col">

                <img src="/assets/images/checkout/creditcards.gif" class="card-icon">
                        
            </div>

        </div>
            
    
    </div>


</div>
