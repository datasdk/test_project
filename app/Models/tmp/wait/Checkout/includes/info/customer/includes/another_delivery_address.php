<?php

    if(!$package_store){

?>

    <div class="customer-info">


        <?php

            $ab = $order["alternative_billing_address"];

        ?>


        <?php if(Layout::get("billing_address")): ?>
                
            <label class="offer">

                <input type="checkbox" name="another_delivery_address" <?php if(!empty($order["alternative_billing_address"])){ echo "checked"; } ?>> 
                
                <?php echo Sentence::translate("Select another delivery address"); ?>
                
            </label>

        <?php endif;?>



        <div class="another_delivery_address_wrapper">


            <?php if($delivery_for_companies): ?>


                <div class="field_container">
                    <span><?php echo ucfirst(Sentence::translate("Company")); ?> *</span>
                    <input type="text" name="billing_organization" value="<?php echo $ordaber["company"]; ?>" 
                    placeholder="<?php echo ucfirst(Sentence::translate("Company")); ?>" autocomplete='organization'>
                </div>

                <div class="field_container float-right">
                    <span><?php echo ucfirst(Sentence::translate("Cvr")); ?> *</span>
                    <input type="number" name="billing_cvr" value="<?php echo $ab["cvr"]; ?>" 
                    placeholder="<?php echo ucfirst(Sentence::translate("Cvr")); ?>">
                </div>



            <?php endif; ?>


                <div class="field_container">
                    <span><?php echo ucfirst(Sentence::translate("First name")); ?> *</span>
                    <input type="text" name="billing_firstname" value="<?php echo $ab["firstname"]; ?>" 
                    class="halfwidth" placeholder="<?php echo ucfirst(Sentence::translate("First name")); ?>" autocomplete='given-name'>
                </div>


                <div class="field_container float-right">
                    <span><?php echo ucfirst(Sentence::translate("Last name")); ?> *</span>
                    <input type="text" name="billing_lastname" value="<?php echo $ab["lastname"]; ?>" 
                    placeholder="<?php echo ucfirst(Sentence::translate("Last name")); ?>" autocomplete='family-name'>
                </div>


            <?php

                if(Delivery::is_delivery($order["delivery_type"])):

                    $class = "";

                    $customer_require_housenumber_floor = 
                    Settings::get("customer_require_housenumber_floor");


                    if(!$customer_require_housenumber_floor){

                        $class = "fullwidth";

                    }

                ?>


                <div class="field_container <?php echo $class; ?>">
                    <span><?php echo ucfirst(Sentence::translate("Address")); ?> *</span>
                    <input type="text" name="billing_address" value="<?php echo $ab["address"]; ?>" 
                    placeholder="<?php echo ucfirst(Sentence::translate("Address")); ?>" autocomplete='address-line1'>
                </div>



            <?php
                
                if($customer_require_housenumber_floor):

            ?>


                <div class="field_container pb-0 float-right">

                    <div class="field_container nowrap">
                        <span><?php echo ucfirst(Sentence::translate("Housenumber")); ?> *</span>
                        <input type="text" name="billing_housenumber" placeholder="<?php echo ucfirst(Sentence::translate("Housenumber")); ?>" value="<?php echo $ab["housenumber"]; ?>" autocomplete="address-line2" >
                    </div>


                    <div class="field_container pb-0  float-right">
                        <span><?php echo ucfirst(Sentence::translate("Floor / door")); ?> </span>
                        <input type="text" name="billing_floor"  placeholder="<?php echo ucfirst(Sentence::translate("Floor / door")); ?>" value="<?php echo $ab["floor"]; ?>" autocomplete="address-line3">
                    </div>
                
                </div>


            <?php

                endif;

            ?>



                <div class="field_container">
                    <span><?php echo ucfirst(Sentence::translate("Zipcode")); ?> *</span>
                    <input type="number" name="billing_zipcode" value="<?php echo $ab["zipcode"]; ?>" 
                    placeholder="<?php echo ucfirst(Sentence::translate("Zipcode")); ?>" autocomplete='postal-code'>
                </div>


                <div class="field_container float-right">
                    <span><?php echo ucfirst(Sentence::translate("City")); ?> *</span>
                    <input type="text" name="billing_city" value="<?php echo $ab["city"]; ?>" 
                    placeholder="<?php echo ucfirst(Sentence::translate("City")); ?>" autocomplete='address-level2'>
                </div>



            <?php

            endif;        

            ?>


        </div>


    </div>

    
<?php

    }

?>