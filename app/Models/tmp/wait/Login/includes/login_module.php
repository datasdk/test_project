<?php

    $msg = "";
    $email = "";

    if(isset($_POST["login_submit"])){

        $email = $_POST["email"];

    }


    if(empty($accept_url)){

        $accept_url = "";

    }


    

?>

    
<div class="login_wrapper">
    
    
    <?php
                                
        if(isset($msg)){ 
                                    
            echo "<div class='msg hidden'>".$msg."</div>";
                                
        }
                                
    ?>
                            
                            
    <div class="content">  
                

        <div class="login_page">


            <form method="post" onsubmit = "return login_userlogin(this)"  autocomplete="on">   
                
                <?php
                    
                    if(!empty($title)){
                        
                        echo "<div class='login-header'>";

                        echo $title;

                        echo "</div>";

                    }
                    
                ?>

                <input name="group_ref_id" type="hidden" value="<?php echo $group_ref_id; ?>">
                <input name="accept_url" type="hidden" value="<?php echo $accept_url; ?>">


                <label>
                                            
                    <span><?php echo Sentence::translate("E-mail"); ?>:</span>
                                                
                    <input name="email"  type="text" required value="<?php echo $email; ?>"  >
                                            
                </label>
                                            
                                            
                <label>
                                                
                    <span><?php echo Sentence::translate("Password"); ?>:</span>
                                                
                    <input name="password" type="password" autocomplete="off" required  >
                                        
                </label>
                                            
                                                                
                <button type="submit" name="login_submit" class="login_submit"><?php echo Sentence::translate("Log in"); ?></button>
                
                
                
                <div id="facebook_login_btn" data-app-id="920656981700883">

                    <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                    </fb:login-button>

                    <div id="status"></div>

                </div>


                                            
                <div class="forotten_password">
                                            
                    <a href="javascript:login_forgot_password()"><?php echo Sentence::translate("Forgot password?"); ?></a>
                                                
                </div>

                <div class="signup-link">

                Ikke medlem? <a href="javascript:open_signup()">Opret dig som bruger</a>

                </div>

            </form>     

        </div>
            

        <div class="forgot_password_page">

            
            <form method="post" onsubmit = "return reset_password(this)" >   


                <div class="login-header">Gendan kodeord</div>

                <label>
                                            
                    <span><?php echo Sentence::translate("E-mail"); ?>:</span>
                                                                
                    <input name="email"  type="text" required value="<?php echo $email; ?>">

                </label>
                                                                
                                                                                    
                <button type="submit" name="login_submit" class="login_submit"><?php echo Sentence::translate("Reset password"); ?></button>


                
            
            </form>


            <div class="forotten_password">
                                            
                <a href="javascript:login_forgot_password()"><?php echo Sentence::translate("Return to login-page"); ?></a>
                                                                                                        
            </div>

            
        </div>


    </div>
    



</div>
      
        
   


            
            