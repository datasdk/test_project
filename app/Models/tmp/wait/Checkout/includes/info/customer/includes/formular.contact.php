<?php
/*
    $params = [ "name" => "checkout" ];

    $forumlar_ref_id = Formular::create($params);


    $params = [ "forumlar_ref_id" => $forumlar_ref_id ];

    $cf = Formular::insert($params);

    

    if($cf){


        echo $cf;


    } else {

*/


    if($delivery_type == "booking"){ $has_address = false; } // skal måske laves om
    if($delivery_type == "pickup"){ $has_address = false; }

?>


<div class="checkout_contact_info 
<?php
 
    if($has_preset_address){

        echo "hidden";

    }

?>
">


    <?php if($delivery_for_companies): ?>


        <div class="field_container">
            <span><?php echo ucfirst(Sentence::translate("Company")); ?></span>
            <input type="text" name="organization" value="<?php echo $company; ?>" 
            placeholder="<?php echo ucfirst(Sentence::translate("Company")); ?>" autocomplete='organization'>
        </div>


        <div class="field_container float-right">
            <span><?php echo ucfirst(Sentence::translate("Cvr")); ?></span>
            <input type="number" name="cvr" value="<?php echo $cvr; ?>" 
            placeholder="<?php echo ucfirst(Sentence::translate("Cvr")); ?>">
        </div>


    <?php endif; ?>


        <div class="field_container">
            <span><?php echo ucfirst(Sentence::translate("First name")); ?> *</span>
            <input type="text" name="firstname" value="<?php echo $firstname; ?>" 
            class="halfwidth" placeholder="<?php echo ucfirst(Sentence::translate("First name")); ?>" autocomplete='given-name'>
        </div>


        <div class="field_container float-right">
            <span><?php echo ucfirst(Sentence::translate("Last name")); ?> *</span>
            <input type="text" name="lastname" value="<?php echo $lastname; ?>" 
            placeholder="<?php echo ucfirst(Sentence::translate("Last name")); ?>" autocomplete='family-name'>
        </div>



    <div class="address-wrapper">


        <?php

            if($has_address):

                $class = "";

                $customer_require_housenumber_floor = 
                Settings::get("customer_require_housenumber_floor");


                if(!$customer_require_housenumber_floor){

                    $class = "fullwidth";

                }

            ?>


            <div class="field_container <?php echo $class; ?>">
                <span><?php echo ucfirst(Sentence::translate("Address")); ?> *</span>
                <input type="text" name="address" value="<?php echo $address; ?>" 
                placeholder="<?php echo ucfirst(Sentence::translate("Address")); ?>" autocomplete='address-line1' class="address">
            </div>


        <?php
            
            if($customer_require_housenumber_floor):
        ?>


            <div class="field_container pb-0 float-right">

                <div class="field_container nowrap">
                    <span><?php echo ucfirst(Sentence::translate("Housenumber")); ?> *</span>
                    <input type="text" name="housenumber" placeholder="<?php echo ucfirst(Sentence::translate("Housenumber")); ?>" value="<?php echo $housenumber; ?>" autocomplete="address-line2"  class="housenumber">
                </div>


                <div class="field_container  float-right">
                    <span><?php echo ucfirst(Sentence::translate("Floor / door")); ?> </span>
                    <input type="text" name="floor"  placeholder="<?php echo ucfirst(Sentence::translate("Floor / door")); ?>" value="<?php echo $floor; ?>" autocomplete="address-line3"  class="floor">
                </div>
            
            </div>


        <?php

            endif;

        ?>


            <div class="field_container">
                <span><?php echo ucfirst(Sentence::translate("Zipcode")); ?> *</span>
                <input type="number" name="zipcode" value="<?php echo $zipcode; ?>" 
                placeholder="<?php echo ucfirst(Sentence::translate("Zipcode")); ?>" autocomplete='postal-code' class="zipcode">
            </div>


            <div class="field_container float-right">
                <span><?php echo ucfirst(Sentence::translate("City")); ?> *</span>
                <input type="text" name="city" value="<?php echo $city; ?>" 
                placeholder="<?php echo ucfirst(Sentence::translate("City")); ?>" autocomplete='address-level2'  class="city">
            </div>



        <?php

            endif;        

        ?>

    </div>


    <div class="field_container">
    <span><?php echo ucfirst(Sentence::translate("Phone")); ?> *</span>
    <input type="number" name="phone" value="<?php echo $phone; ?>" 
    placeholder="<?php echo ucfirst(Sentence::translate("Phone")); ?>" autocomplete='tel'>
    </div>


    <div class="field_container float-right">
    <span><?php echo ucfirst(Sentence::translate("E-mail")); ?> *</span>
    <input type="text" name="email" value="<?php echo $email; ?>" 
    placeholder="<?php echo ucfirst(Sentence::translate("E-mail")); ?>"  autocomplete='email'>
    </div>


    <?php

        if($has_comment){

    ?>

        <div class="field_container fullwidth">
            <span><?php echo Sentence::translate("Comment"); ?></span>
            <textarea name="comment" placeholder="<?php echo Sentence::translate("Please attach a comment on the order (max 200 characters)"); ?>" 
            maxlength="200"><?php if(isset($order["comment"])){ echo $order["comment"]; } ?></textarea>
        </div>

    <?php

        }

    ?>



</div>


  