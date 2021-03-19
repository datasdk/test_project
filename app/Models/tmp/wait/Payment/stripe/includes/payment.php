<?php

    $ccrg = Customer::get_ccrg();




    if($form){


        $onsubmit = "return stripe_submit_payment(this,\"payment\")";
    

        echo "
        <form 
        class='stripe-payment-form' 
        action='' 
        method='post'
        onsubmit='".$onsubmit."'
        >"; 
        
    }

   

?>


    <div 
    class="stripe-payment <?php if($active_card){ echo "has_saved_card"; } ?>" 
    data-public_key="<?php echo $public_key; ?>"
    >
    

        <input type="hidden" name="return_url"  value="<?php echo $return_url; ?>">



        <div class="card_element_wrapper">


            <?php
                
                if(Payment::is_test()){

                    echo "<div class='msg msg-alert mb-3'>Dette er en test-version af betalings-vinduet. Penge trækkes ikke på kortet.</div>";

                }       
                
                
                
            
            ?>

            
            <div class="payment-header">


                <div class='payment-title'>
                    

                    <?php

                        echo Sentence::translate("Online payment");

                        echo Payment::icons();

                    ?>

                </div>
                
                

            </div>


            <!-- Used to display Element errors. -->
            <div id="card-errors" role="alert"></div>
                    
            <?php

                $cardholder = Sentence::translate("Cardholder name");
            
            ?>

      
            <input id="cardholder" name="cardholder" class="cardholder"
            placeholder="<?php echo $cardholder; ?>" required>


            <!-- A Stripe Element will be inserted here. -->
            <div id="card-element-<?php echo time();?>" class="card_element"></div>


            



            <?php
                      
                if($has_card_save or $has_terms){


                    echo "<div class='stripe-payment-options'>";


                        if($has_card_save)
                        if($type == "payment")
                        if(Customer::is_logged_in()){
        
                            echo '
                            <label>
                                <input type="checkbox" name="save_card" value="1"> 
                                Gem mit kort til fremtidig brug
                            </label>
                            ';
        
                        }
        
            
                        if($has_terms){
        
                            echo Terms::accept();
        
                        }


                    echo "</div>";

                }

             
            ?>

    </div>    

<?php

    
    if($form){
                        
            echo '<button id="card-button">';
                    
                echo $button_text;
                
            echo'</button>';

        echo '</form>';

    }


   // include(__DIR__."/stripe_info.php");

    
?> 


</div>
    