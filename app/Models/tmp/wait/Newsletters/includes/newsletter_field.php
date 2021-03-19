<div class="newsletter_wrapper">
    

    <div class="newsletter_content">


        <form method="post">

        
            <?php
                        
                if($show_title){ echo '<h3>'.Sentence::translate("Subscribe to newsletter").'</h3>';}
                        
            ?>


            <label class="email-field">
                
                <input type="text" name="email" placeholder="E-mail adresse" value="<?php echo $default_email; ?>"/> 
            
            </label>


            <div class="button-wrapper">

                <label>
                    
                    <button type="button" name="subscribe" class="btn btn-default"><?php echo Sentence::translate("Subscribe");?></button>  
                
                </label>


                <label>
                    
                    <button type="button" name="unsubscribe" class="btn btn-default"><?php echo Sentence::translate("Unsubscribe");?></button>  
                
                </label>
            
            </div>


             <div class="terms_of_trade_wrapper">

                <label>
                <input type="checkbox" name="terms_of_trade" value="1">
                <?php echo Sentence::translate("I accept");?> <?php echo $_SERVER["HTTP_HOST"];?>'s <a href="javascript:terms_open_private_private_policy()"><?php echo Sentence::translate("Terms and private policy");?></a>
                </label>

            </div>


        </form>
    
    
    </div>

</div>