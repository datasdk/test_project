<?php

    if($has_header){

        echo '
        <div class="account_title">
            <h3>Skift adgangskode</h3>
            <p>Her kan du tilmelde og afmelde dig vores nyhedsbrev</p>
        </div>
        ';
    }

?>



<div class="acccount_content">


    <form id="account_change_password" method="post" onsubmit="return account_change_password(this)">


        <div class="form-group">

            <span><?php echo Sentence::translate("Change password");?></span>

            <input type="password" name="password" value="">
        
        </div>


        <div class="form-group">

            <span><?php echo Sentence::translate("Repeat password");?></span>
            
            <input type="password" name="password_repeat" value="">

        </div>


        <button><?php echo Sentence::translate("Change password");?></button>


    </form>


</div>