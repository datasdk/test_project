<?php

    if($type == sha1("delivery")){


        $from = strtotime($from_time,$date_timestamp);

        $to   = strtotime($to_time,$date_timestamp);




        //echo $date." ".$to_time."<br>";
        //echo date("d/m Y H:i",$from_time)." ".date("d/m Y H:i",$to_time)."<br>";
        //echo date("d/m Y H:i",$timestamp)." ".date("d/m Y H:i",$to)."<br>";
        
        if($timestamp < $from){ $add = false; }
        if($timestamp > $to){   $add = false; }

  

    }

?>