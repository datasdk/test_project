<?php

    include(__DIR__."/includes/header.php");

    if($has_header){

        echo '
        <div class="account_title">
            <h3>'.Sentence::translate("My profile").'</h3>
            <p>'.Sentence::translate("Here you can change your user information").'</p>
        </div>
        ';

    }

?>







<div class="acccount_content">


    <div class="account-info">
        

        <form method="post" action="" onsubmit="return account_update_customer(this)" >
    
            <div class="clearfix">

                <div class="field_container">
                    <label for="organization"><?php  echo ucfirst(Sentence::translate("Firma"));?></label>
                    <input type="text" name="organization" value="<?php echo $company; ?>"  autocomplete="organization">
                </div>


                <div class="field_container pr-0">
                    <label for="cvr"><?php  echo ucfirst(Sentence::translate("Cvr"));?></label>
                    <input type="number" name="cvr" value="<?php echo $cvr; ?>" >
                </div>


                <div class="field_container">
                    <label for="firstname"><?php  echo ucfirst(Sentence::translate("First name"));?></label>
                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="halfwidth"  autocomplete="given-name">
                </div>


                <div class="field_container pr-0">
                    <label for="lastname"><?php  echo ucfirst(Sentence::translate("Last name"));?></label>
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" autocomplete="family-name">
                </div>


                <div class="field_container">
                    <label for="address"><?php  echo ucfirst(Sentence::translate("Address"));?></label>
                    <input type="text" name="address" value="<?php echo $address; ?>" autocomplete="address-line1">
                </div>

           

                    <div class="field_container  pb-0  pr-0">

                        <div class="field_container pt-0">
                            <label for="housenumber"><?php  echo ucfirst(Sentence::translate("Housenumber"));?></label>
                            <input type="text" name="housenumber" value="<?php echo $housenumber; ?>" autocomplete="address-line2">
                        </div>


                        <div class="field_container pr-0  pt-0">
                            <label for="floor"><?php  echo ucfirst(Sentence::translate("Floor/door"));?></label>
                            <input type="text" name="floor"  value="<?php echo $floor; ?>" autocomplete="address-line3">
                        </div>
                    
                    </div>
                
            


                <div class="field_container">
                    <label for="zipcode"><?php  echo ucfirst(Sentence::translate("Zipcode"));?></label>
                    <input type="number" name="zipcode" value="<?php echo $zipcode; ?>" autocomplete="postal-code">
                </div>


                <div class="field_container pr-0">
                    <label for="city"><?php  echo ucfirst(Sentence::translate("City"));?></label>
                    <input type="text" name="city" value="<?php echo $city; ?>" autocomplete="address-level2">
                </div>


                <div class="field_container">
                    <label for="phone"><?php  echo ucfirst(Sentence::translate("Phone"));?></label>
                    <input type="number" name="phone" value="<?php echo $phone; ?>" autocomplete="tel">
                </div>


                <div class="field_container pr-0">
                    <label for="email"><?php  echo ucfirst(Sentence::translate("E-mail"));?></label>
                    <input type="text" name="email" value="<?php echo $email; ?>"  autocomplete="email">
                </div>
            
            </div>


    

            <!--
            <div class="clearfix pt-4 pb-4">
                <div><label for="billeder"><?php  echo ucfirst(Sentence::translate("Profil-billede"));?></label></div>
                <input type="file" name="image" value="">
            </div>
            -->

        

            <button class=" "><?php echo Sentence::translate("Update information");?></button>


        </form>




    </div>


</div>
