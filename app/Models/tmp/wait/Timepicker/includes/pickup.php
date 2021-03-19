<?php

    if($type == sha1("pickup")){



        $from = strtotime($from_time,$date_timestamp);

        $to   = strtotime($to_time,$date_timestamp);


        if($timestamp < $from){ $add = false; }
        if($timestamp > $to){   $add = false; }


    }

?>