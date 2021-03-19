
<div class="account">


    <div class="account_navigationbar">

 


        <?php
            
            $newsletters = Settings::get("newsletters");

        
            $url = Languages::lang_url();
            
        
            if($has_profile){

                echo "<a href='".$url ."/account/my_account'><i class='fas fa-user'></i> ".Sentence::translate("My profile")."</a>";


            }
            

            if($has_order){

                echo "<a href='".$url ."/account/orders'><i class='fas fa-shopping-basket'></i> ".Sentence::translate("My orders")."</a>";

            }
            

            if($has_creditcard){
           
                echo "<a href='".$url ."/account/payment'><i class='fas fa-credit-card'></i> ".Sentence::translate("Payment card")."</a>";

            }
            
            if($newsletters and $has_newsletter){

                echo "<a href='".$url ."/account/newsletter'><i class='fas fa-envelope'></i> ".Sentence::translate("Newsletters")."</a>";

            }
            

            if($has_change_password){

                echo "<a href='".$url ."/account/password'><i class='fas fa-unlock-alt'></i> ".Sentence::translate("Change password")."</a>";

            }


            if($has_logoff){

                echo "<a href='".$url ."/account/log_off'><i class='fas fa-power-off'></i> ".Sentence::translate("Logout")."</a>";

            }


        ?>
            
           
    </div>


    <div class="acccount_wrapper">


     
