<?php

    if($show_wrapper){ echo '<div class="information_wrapper">'; }

    $required = "<span class='required'>*</span>";


    $formular_id = $form["formular_id"];



    if(!empty($form["fields"])){

        
        $sf = $form["fields"];
        $fn = $form["field_names"];
     

        if(empty($sf)){
            
            $sf = self::$standard_fields;

        }


        
        echo "<!-- formular_id: ".$formular_id."-->";


        foreach($sf as $val){
            
            
            $name = $val["name"];
            $type = $val["type"];

          
            if($name == "comment"){ $type = "textarea"; }


            if($ignore)
            if(in_array($name,$ignore)){ continue; }

            

            $label = str_replace("_"," ",$name);


            // change label
            if(
                ($name == "first_name" and !in_array("last_name",$fn))
                 or
                ($name == "last_name" and !in_array("first_name",$fn))
            ){ $label = "name"; }



            echo '<div class="form-group">';


                echo '<label for="'.$name.'">'.ucfirst(Sentence::translate($label)).'</label>';


                if($type == "textarea"){


                    echo '
                    <textarea name="'.$name.'" type="'.$type.'" 
                    placeholder="'.ucfirst(Sentence::translate($label)).'" ></textarea>';


                } else {


                    echo '
                    <input name="'.$name.'" type="'.$type.'" value="" 
                    placeholder="'.ucfirst(Sentence::translate($label)).'" >';


                }

                
            echo '</div>';
            
            // id="organisation" name="organisation" autocomplete="organization" 

        }

        
    }

?>






    <?php if(isset($sf["company"])){ ?>
  
        <div class="form-group">
                
            <label for="Firma"></i><?php echo ucfirst(Sentence::translate("Company")); ?>  </label>
            <input type="text" id="organisation" name="organisation" value="" placeholder="<?php echo  ucfirst(Sentence::translate("Company")); ?>" autocomplete='organization' >

        </div>

    <?php } ?>


    
    <?php if(isset($sf["cvr"])){ ?>
        <div class="form-group">
                
            <label for="Firma"></i><?php echo ucfirst(Sentence::translate("Cvr")); ?> </label>
            <input type="text" id="cvr" name="cvr" value="" placeholder="<?php echo ucfirst(Sentence::translate("Cvr")); ?>" autocomplete='' >

        </div>
    <?php } ?>


    <?php if(isset($sf["ean"])){ ?>
        <div class="form-group">
                
            <label for="ean"></i><?php echo ucfirst(Sentence::translate("Ean")); ?> </label>
            <input type="text" id="ean" name="ean" value="" placeholder="<?php echo ucfirst(Sentence::translate("Ean")); ?>" autocomplete='' >

        </div>
    <?php } ?>

    <?php 
    
        if(isset($sf["first_name"])){
    
            $firstname_text = ucfirst(Sentence::translate("Firstname"));
            if(!isset($sf["last_name"])){ $firstname_text = ucfirst(Sentence::translate("Name")); }

    ?>

        <div class="form-group">
                
            <label for="first_name"><?php echo $firstname_text; ?> <?php echo $required; ?> </label>
            <input type="text" id="firstname" name="firstname" value="" placeholder="<?php echo $firstname_text; ?>" autocomplete='given-name' required>

        </div>
    
    <?php } ?>


    <?php if(isset($sf["last_name"])){ ?>
    <div class="form-group">
            
        <label for="last_name"></i><?php echo ucfirst(Sentence::translate("Lastname")); ?> <?php echo $required; ?></label>
        <input type="text" id="lastname" name="lastname" value="" placeholder="<?php echo ucfirst(Sentence::translate("Lastname")); ?>" autocomplete='family-name' required>

    </div>
    <?php } ?>


    <?php if(isset($sf["address"])){ ?>


        <div class="form-group">
                
            <label for="address"><?php echo ucfirst(Sentence::translate("Address")); ?> <?php echo $required; ?> </label>
            <input type="text" id="address" name="address" value="" placeholder="<?php echo ucfirst(Sentence::translate("Address")); ?>" autocomplete='address-line1' required>

        </div>
        

        <?php

            if(Settings::get("customer_require_housenumber_floor")){

        ?>

            <div class="floor-door">

                <div class="form-group">
                    
                        <label for="housenumber"><?php echo ucfirst(Sentence::translate("Housenumber")); ?> <?php echo $required; ?></label>
                        <input type="text"  name="housenumber" value="" placeholder="<?php echo ucfirst(Sentence::translate("Housenumber")); ?>" autocomplete='address-line2' required>
            
                </div>

                <div class="form-group">
                        
                    <label for="floor"><?php echo ucfirst(Sentence::translate("Floor / Door")); ?> </label>
                    <input type="text" name="floor" value="" placeholder="<?php echo ucfirst(Sentence::translate("Floor / Door")); ?>" autocomplete='address-line3' required>
            
                </div>

            </div>


        <?php } ?>



    <?php }?>


    <?php if(isset($sf["city"])){ ?>

        <div class="form-group">
                
            <label for="city"></i><?php echo ucfirst(Sentence::translate("City")); ?> <?php echo $required; ?></label>
            <input type="text" id="city" name="city" value="" placeholder="<?php echo ucfirst(Sentence::translate("City")); ?>" autocomplete='address-level2' required>
            
        </div>

        <div class="form-group">
                
            <label for="zipcode"><?php echo ucfirst(Sentence::translate("Zipcode")); ?> <?php echo $required; ?></label>
            <input type="text" id="zipcode" name="zipcode" value="" placeholder="<?php echo ucfirst(Sentence::translate("Zipcode")); ?>" autocomplete='postal-code' required>

        </div>

    <?php }?>


    <?php if(isset($sf["email"])){ ?>
    <div class="form-group">
            
        <label for="email"><?php echo ucfirst(Sentence::translate("E-mail")); ?> <?php echo $required; ?></label>
        <input type="text" id="email" name="email" value="" placeholder="<?php echo ucfirst(Sentence::translate("E-mail")); ?>" autocomplete='email' required>

    </div>
    <?php }?>


    <?php if(isset($sf["phone"])){ ?>
    <div class="form-group">
            
        <label for="phone"><?php echo ucfirst(Sentence::translate("Phone")); ?> <?php echo $required; ?></label>
        <input type="text" id="phone" name="phone" value="" placeholder="<?php echo ucfirst(Sentence::translate("Phone")); ?>" autocomplete='tel-national' required>

    </div>
    <?php }?>


    <?php if(isset($sf["comment"])){ ?>
    
        <div class="form-group">

            <label for="massage"><?php echo ucfirst(Sentence::translate("Comment")); ?> </label>
            <textarea id="massage" name="comment" placeholder="<?php echo ucfirst(Sentence::translate("Comment")); ?>" ></textarea>

        </div>

    <?php }?>

    
    <?php if(isset($sf["newsletter_email"])){ ?>
    
        <div class="w100">
                
            <label>
            <input type="checkbox" id="newsletter_email" name="newsletter_email" value="">
            
            <?php echo ucfirst(Sentence::translate("Phone")); ?>

            </label>
            
        </div>

    <?php }?>


    
    <?php if(isset($sf["newsletter_sms"])){ ?>
    
        <div class="w100">
                
            <label>
            <input type="checkbox" id="newsletter_sms" name="newsletter_sms" value="" >
                
                <?php echo ucfirst(Sentence::translate("Subscribe to SMS newsletter")); ?>
            
            </label>
            
        </div>

    <?php } ?>


<?php

    if($show_wrapper){ echo "</div>"; };

?>

   


 
