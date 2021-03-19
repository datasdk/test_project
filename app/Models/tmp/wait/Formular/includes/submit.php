<?php
  
    if($has_form){

        
        echo reCaptsha::insert();

        if(!$button_text){

            $button_text = "Send message";

        }
        
                                                              
        echo "
        <div class='contact-submit'>
                                                    
            <div class='submit'>
                <button class='contact_submit_button'>".Sentence::translate($button_text)."</button>
            </div>
                    
        </div>
        ";

    }

?>