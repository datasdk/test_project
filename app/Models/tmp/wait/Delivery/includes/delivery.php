<?php

    if($type == "delivery"){


        $time_frame     = Settings::get("delivery_timeframe");
        $time_interval  = Settings::get("delivery_interval_time");


        $sql = "select * from delivery_time";

        if(isset($company_ref_id)){ $sql .= "and company_ref_id = ".$company_ref_id; }


        
        $result = mysqli_query($mysqli,$sql);

        while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC)){


            $from_hours = $row["from_hours"];

            $from_minutes = str_pad($row["from_minutes"], 2, '0', STR_PAD_LEFT);

            $to_hours = $row["to_hours"];

            $to_minutes = str_pad($row["to_minutes"], 2, '0', STR_PAD_LEFT);

            

            $interval[$row["day"]]["from"] = ($from_hours.":".$from_minutes);

            $interval[$row["day"]]["to"]   = ($to_hours.":".$to_minutes);

            $interval[$row["day"]]["active"] = $row["active"];

            
        }


    }

?>