<?php

    if($description){

?>

    <div class="tabs_wrapper">
            

        <div class="tabs">

            <ul>
            <li><a href="#tabs-1">Beskrivelse</a></li>
            <!--
            <li><a href="#tabs-2">Specifikationer</a></li>
            <li><a href="#tabs-3">Prisoversigt</a></li>
            -->
            </ul>

            <div id="tabs-1">
                
                <?php

                    echo "<p>".ucfirst($description)."</p>";

                ?>
            
            </div>

            <div id="tabs-2">

            </div>

            <div id="tabs-3">

            </div>

        </div>
    
    </div>

<?php

    }

?>