
</div>

<?php

    $info = Company::first();

?>


<div class="bottom-info">
            
    <?php

        echo Components::Text("bottom-text");
            
   ?>
          
</div>



<div class="footer-wrapper ">

    <div class="footer-row">

        <div class="footer-col">
            
            <h4><?php echo Sentence::translate("Address"); ?></h4>
            
            <?php

                echo "<div>".$info->company."</div>";
                echo "<div>".$info->address."</div>";
                echo "<div>".$info->zipcode." ".$info["city"]."</div>";
                echo "<div>Cvr: ".$info->cvr."</div>";
            
            ?>

        </div>  


        <div class="footer-col">
            
            <h4><?php echo Sentence::translate("Contact us"); ?></h4>

            <?php

                echo Company::phone();

                echo Company::email();

            ?>       
        
        </div>  


        <div class="footer-col">

            <h4><?php echo Sentence::translate("Opening hours"); ?></h4>

            <?php

                echo Components::opening_hours();
            
            ?>
            
        </div>  


        <div class="footer-col">
    
            <h4><?php echo Sentence::translate("Follow us"); ?></h4>
            
            <?php Components::some(); ?>

            <div class="mt-3"><a href="/terms"><?php echo Sentence::translate("Terms"); ?></a></div>
            
        </div>  

    </div>


</div>



<div class="created-by">

  

</div>