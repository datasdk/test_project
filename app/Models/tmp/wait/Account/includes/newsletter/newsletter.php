<?php

    $customer = Customer::get();

    $email = $customer["email"];

    
    if($has_header){

        echo '
        <div class="account_title">
            <h3>Tilmeld / afmeld nyhedsbreve</h3>
            <p>Her kan du tilmelde og afmelde dig vores nyhedsbrev</p>
        </div>
        ';

    }

?>




<div class="acccount_content">

    <?php

        Newsletter::get(true,$email);
    
    ?>


   


</div>