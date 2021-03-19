<?php

    
    $result = Delivery::accessible();


    if(!$result["ok"])
    if(!empty($result["msg"])){

        echo "<div class='alert alert-info'>".$result["msg"]."</div>";

    }


?>