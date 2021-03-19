<?php


    $checkout_customer_create_account = 
    Session::get("checkout_customer_create_account");

    $checkout_customer_accept_newsletter = 
    Session::get("checkout_customer_accept_newsletter");

    $delivery_for_companies = 
    Settings::get("delivery_for_companies");

    $enable_billing_address = 
    Order::get_alternative_delivery_address();


    $password = "";


    if(!empty(Session::get("checkout_customer_new_password"))){
        
        $password = Session::get("checkout_customer_new_password");

    }
    

    $customer_ref_id = Customer::getCustomerId();

    $c = Customer::get($customer_ref_id);
    
    $lang = Languages::lang_url();

    

    //

    $company    = "";
    $cvr        = "";
    $firstname  = "";
    $lastname   = "";
    $address    = "";
    $housenumber = "";
    $floor      = "";
    $zipcode    = "";
    $city       = "";
    $phone      = "";
    $email      = "";

    $has_preset_address = false;


    if($order){

        
        $company    = $order["company"];
        $cvr        = $order["cvr"];
        $firstname  = $order["firstname"];
        $lastname   = $order["lastname"];
        $address    = $order["address"];
        $housenumber= $order["housenumber"];
        $floor      = $order["floor"];
        $zipcode    = $order["zipcode"];
        $city       = $order["city"];
        $phone      = $order["phone"];
        $email      = $order["email"];


    } else if($c){


        $company    = $c["company"];
        $cvr        = $c["cvr"];
        $firstname  = $c["firstname"];
        $lastname   = $c["lastname"];
        $address    = $c["address"];
        $housenumber= $c["housenumber"];
        $floor      = $c["floor"];
        $zipcode    = $c["zipcode"];
        $city       = $c["city"];
        $phone      = $c["phone"];
        $email      = $c["email"];

        

    }     
 
    if($c){

        $has_preset_address = true;

    }


?>


<div class="customer-info">
    

    <?php
        

        if($has_preset_address){


            include(__DIR__."/formular.contact.preview.php");


        } 



        

            include(__DIR__."/formular.contact.php");


        
  


        

        if(!$customer_ref_id ){
            
     
            
            // gør det muligt for admin at vælge om der skal kunne oprettes en bruger
            
            if(!$require_account)
            if($add_customer){
            
    ?>
            
                <label class="offer">
                        
                    <input type="checkbox" name="create_account" 
                    <?php if($checkout_customer_create_account){ echo "checked"; } ?>> 
                        
                    <?php

                        echo Sentence::translate("Create account"); 
                        
                    ?>
                        
                </label>


        <?php

            }
            
        ?>
            
            
        <div class="select_password field_container w-100">
            <span><?php echo Sentence::translate("Password"); ?></span>
            <input type="password" name="password" value="<?php echo $password; ?>" 
            placeholder="<?php echo Sentence::translate("Choose password"); ?>"  
            autocomplete='new-password' >
        </div>
        

        
    <?php 
        
        } 

    ?>



        <?php

            if($has_newsletter){
        
        ?>


            <label class="offer">

                <input type="checkbox" name="newsletter" <?php if($checkout_customer_accept_newsletter){ echo "checked"; } ?>> 
                    
                <?php 
                        
                    echo Sentence::translate("Yes please, I would like to receive offers from"); 
                        
                    echo " ".$_SERVER["HTTP_HOST"]." ";

                    echo Sentence::translate("by E-mail"); 
                
                ?>
                    
            </label>

    <?php

        }
        
    ?>

</div>

