<div class="signup-wrapper">


    <form id="account_signup_formular" method="post" onsubmit="return account_signup(this)"> 


        <?php
        
            if(!empty($title)){
                    
                echo '<div class="login-header">';
                    
                echo $title;

                echo '</div>';

            }
                

        ?>


        <div class="signup-content">


            <input type="hidden" name="accept_url" value="<?php echo $accept_url; ?>">


            <?php

                if($formular_name){


                    $formular_ref_id = 
                    Formular::create(["name"=>$formular_name]);

                
                    $o = [
                    "formular_ref_id"=>$formular_ref_id,
                    "show_wrapper"=>0,
                    "has_form"=>0,
                    "ignore"=>["email"]
                    ];
                

                    echo Formular::insert($o); 


                }
                

                
                $fields = [];


                /*
               


                if($firstname){ $fields[] = "firstname"; }
                
                if($lastname){ $fields[] = "lastname"; }
                

              


                if($address){ $fields[] = "address"; }
                
                if($housenumber){ $fields[] = "housenumber"; }
                
                if($floor){ $fields[] = "floor"; }
                
                if($zipcode){ $fields[] = "zipcode"; }
                
                if($city){ $fields[] = "city"; }
                
            */


                $fields[] = "email";
                $fields[] = "password";


                foreach($fields as $name){

                    $class = "col-left";
                    $type = "text";
                    
                    if($name == "lastname"){ $class = "col-right"; }
                    if($name == "password"){ $type = "password"; }

                    
                    $label = ucfirst(Sentence::translate($name));
                    $autocomplete = $name;

                    
                    echo '
                    <div class="'.$class.'">
                    <label for="'.$name.'">'.$label.':</label>
                    <input type="'.$type.'" name="'.$name.'" value="" 
                    placeholder="'.$label.'" autocomplete="'.$autocomplete.'">
                    </div>
                    ';

                }

                

           ?>
            
            

            <div class="terms_of_trade_wrapper">

                <label>
       
                <input type="checkbox" name="terms_of_trade" value="1">

                <?php echo ucfirst( Sentence::translate("I accept") );?> <?php echo $_SERVER["HTTP_HOST"];?>'s
           
                
                <a href="javascript:terms_open_private_private_policy()"> 

                <?php echo Sentence::translate("Terms and privacy policy");?></a>
                
                </label>

            </div>


            <?php

                echo reCaptsha::insert();
            
            ?>


            <button class="submit"><?php echo Sentence::translate("Sign up");?></button>


            <div class="login-link">

                Allerede medlem? <a href="javascript:open_login()">Log på her</a>

            </div>

            
        </div>



    </form>

</div>


