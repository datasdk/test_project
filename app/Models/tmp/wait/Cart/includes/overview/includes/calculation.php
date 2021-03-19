<?php

    $p = [
        "type"=>"overview",
        "hide_completed"=>$hide_completed
    ];



    Reciept::set_calculation($p);



    Shop::insert_free_delivery();

?>
