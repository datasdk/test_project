<?php
    
    $url = Languages::lang_url();

    if($has_header){

        echo '
        <div class="account_title">
        <h3>Kvittering</h3>
        <p>Få et overblik over din bestilling</p>
        ';
          
        echo '
        <a href="'.$url.'/account/orders">
        '.Sentence::translate("Back to overview").'
        </a>';

        echo '</div>';
        

    }


    


?>


<div class="acccount_content">

    
    


    <?php

        $order_ref_id = 0;
        
        if(isset($_GET["order_ref_id"])){

            $order_ref_id = $_GET["order_ref_id"];

        }
        

        if(empty($order_ref_id)){


            echo "<div class='alert alert-primary'>";
            
            echo Sentence::translate("There is no receipt for this order");
            
            echo "</div>";
            

        } else {

            echo Reciept::create($order_ref_id);           
            
        }
   

    ?>


    

</div>