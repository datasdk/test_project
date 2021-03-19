<div id="<?php $name?>" 
data-change_page_callback="<?php echo $change_page_callback; ?>"
data-submit_callback="<?php echo $submit_callback; ?>"
class="wizard">

    <?php

        $i = 0;

        foreach($pages as $content){


            echo "<div class='wizard_page_".$i."' wizard_page>";

                echo $content;

                echo "<div class='wizard_footer'>";

                    
                    echo "<button>Tilbage</button>";

                    echo "<button>næste</button>";


                echo "</div>";

            echo "</div>";

            $i++;

        }


    ?>

</div>