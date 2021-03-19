<?php

    if(isset($_GET["type"])){


        $type = $_GET["type"];


        if($type == "standard"){
    
            echo Terms::standard();
    
        } else {
    
            echo Terms::get($type);
    
        }


    }

?>