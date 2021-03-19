<?php

    if($type == sha1("standard")){

    

        $from = strtotime($openingtime,$date_timestamp);

        $to   = strtotime($closingtime,$date_timestamp);


        if($timestamp < $from){  $add = false; }
        if($timestamp > $to){    $add = false; }


    }

?>