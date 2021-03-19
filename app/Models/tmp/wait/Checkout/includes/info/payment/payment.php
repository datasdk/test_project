<?php

    include(__DIR__."/includes/header.php");



?>



    <div class="payment-option sektion">

        <?php

            // delivery

            echo '<div class="option-groupe">';

                    
            // payment
                        
            echo '<div class="option-groupe last">';


                echo "<h4>".ucfirst(Sentence::translate("choose payment"))."</h4>";

                
                    include(__DIR__."/includes/payment.php");


                echo '</div>';

                    
            echo '</div>';

        ?>

    </div>


