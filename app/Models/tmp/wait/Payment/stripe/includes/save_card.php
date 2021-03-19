<?php


    if(!$customer_ref_id){

        $customer_ref_id = Customer::getCustomerId();

    }



    if($customer_ref_id){


     

      //  $card = Customer::card($customer_ref_id);



        if($active_card){


            $card_btn_txt = "Tilføj kort";

            $card_type = $brand;

            $card_hint = "**** **** **** ".$last4;

            $card_btn_txt = "Fjern kort";


            $src = "/assets/images/checkout/";


            if($card_type == "visa"){               $src.= "visa.png"; }
            else if($card_type == "mastercard"){    $src.= "mastercard.png"; }
            else if($card_type == "maestro"){       $src.= "maestro.png"; }
            else if($card_type == "visaelectron"){  $src.= "visaelectron.png"; }
            else {  $src.= "cvc.gif"; }


            
            $title = Sentence::get($title);

            


            
            echo "<div class='save_card'>";



                echo "<div class='payment-header'>";

                    echo "<div class='payment-title'>";
                            

                        echo "Online betaling";

                        echo Payment::icons();

                    echo "</div>";

                echo "</div>";

                

                echo '<div class="payment-header">';

                   // echo '<div class="payment-title">'.$title.'</div>';

                echo "</div>";



                $onsubmit = "return stripe_submit_payment(this,'savecard')";

                
                if($active_card){ $onsubmit = "return stripe_submit_payment(this,\"redraw\")"; }
                
            

                
                echo "
                <form 
                class='stripe-payment-form' 
                action='' 
                method='post' 
                onsubmit='".$onsubmit."'>"; 



          

                    echo "<input type='hidden'  name='return_url'  value='".$return_url."'>";


                    echo "<div class='cart_hint'>";


                        echo "<img src='".$src."'>";
                                    

                        echo "<span class='hint'>";

                            echo "<div class='hint-title'>Gemt betalingskort</div>";

                            echo "<div class='hint-cardnumber'>".$card_hint."</div>";
                        
                        echo "</span>";

                    
                        echo "<button type='button' class='shift_card_btn'>".$card_btn_txt."</button>" ;


                    echo "</div>";


                    
                    if($has_terms){

                        echo Terms::accept();
    
                    }


                    if($type == "payment"){

                        echo "<button type='submit' id='card-button' >".$button_text."</button>";

                    }
                        
                    
                echo "</form>";


                

            echo "</div>";



            


        } 
     
        
    }


    
            
?>