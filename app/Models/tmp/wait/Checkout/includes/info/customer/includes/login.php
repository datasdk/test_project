    <div class="login_header">

    
        <h4 class="sektion-title">

            <?php echo Sentence::translate("Contact information")?>

        </h4>

                <?php
                    
                    if($has_login)
                    if(Customer::is_logged_in()){
                    
                ?>
                    
                        <a href = "javascript:checkout_logoff()"><i class="fas fa-user-alt"></i>
                            
                            <?php echo Sentence::translate("Log off");?>

                        </a>


                <?php

                    } else {

                        $lang = Languages::lang_url();

                        $url = $lang .'/checkout/info';

                ?>
                    
                        <a href = "javascript:open_login('<?php echo $url; ?>')"><i class="fas fa-user-alt"></i> 
                            
                            <?php echo Sentence::translate("Existing customer");?>

                        </a>

                <?php

                    }
                        
                ?>
                            
            </div>