<?php

    $days_in_advance = Settings::get("processing_time_days"); 



    $sql = "select * from delivery_time";

    $result = array_values(DB::select($sql));

    $today = date("N");
    $today_end_time = 0;

    // closeing days on opening time



    $closingdays = array();
    
    foreach($result as $val){

        $day = $val["day"];

        $from_hours = $val["from_hours"];
        $from_minutes = $val["from_minutes"];
        $to_hours = $val["to_hours"];
        $to_minutes = $val["to_minutes"];

        $active = $val["active"];

        if($openinghours[$day]["closed"]){ $active = 0; }

        // important
        // convert sunday (7th day) to 0 because the datpicker sees sandays daynumber as 0
        if($day == 7){ $day = 0; }


        if($today == $day){ 
            
            $today_end_time = strtotime($to_hours.":".$to_minutes);

        }

        $closingdays[$day] = $active;

    }



?>