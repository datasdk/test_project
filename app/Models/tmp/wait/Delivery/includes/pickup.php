<?php

    if($type == "pickup" or $type == "booking"){

        
        $time_frame     = Settings::get("pickup_timeframe");
        
        $time_interval  = Settings::get("pickup_interval_time");


        $sql = "select * from delivery_pickup_time";
        
        if(isset($company_ref_id)){ $sql .= "and company_ref_id = ".$company_ref_id; }


        
        $result = mysqli_query($mysqli,$sql);

        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){


            $from_hours = $row["from_hours"];

            $to_hours = $row["to_hours"];

            $from_minutes = $row["from_minutes"];

            $to_minutes = $row["to_minutes"];
            

            if($from_minutes < 10){ $from_minutes = "0".$from_minutes; }

            if($to_minutes < 10){ $to_minutes = "0".$to_minutes; }



            $interval[$row["day"]]["from"] = ($from_hours.":".$from_minutes);

            $interval[$row["day"]]["to"]   = ($to_hours.":".$to_minutes);

            $interval[$row["day"]]["active"] = $row["active"];


        }

    }
    
   
?>