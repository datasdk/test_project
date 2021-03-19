<div class="account_login_wrapper">


    <div class="account-login-header">
                    
        <?php
                    
            echo Text::get("account-headline");
                    
        ?>
                    
    </div>


    <div class="container-fluid">


        <div class="row">


            <div class="col">
            
                <h3><?php echo Sentence::translate("Log in");?></h3>

                <?php
                    
                    echo Text::get("login-p-text");
                    
                ?>

                <?php

                    Login::insert();

                ?>

            </div>


            <div class="col">

                <h3><?php echo Sentence::translate("Create user");?></h3>

                <?php
                    
                    echo Text::get("signup-p-text");
                    
                ?>

                <?php

                    Signup::insert();
                
                ?>

            </div>


        </div>


    </div>


</div>