<?php

    foreach($closingdays as $day => $active){

        echo "<input type='hidden' class='closing_day_".$day."'  value='".$active."' readonly>";

    }

?>