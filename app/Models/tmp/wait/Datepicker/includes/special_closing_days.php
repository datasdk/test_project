<?php


    $company_ref_id = 1;

    OpeningHours::load();

    $special_closing_days = OpeningHours::$closing_days;


    if(!empty($special_closing_days)){


        $special_closing_days = $special_closing_days[$company_ref_id];


    
        foreach($special_closing_days as $timestamp){
    
    
            $timestamp = strtotime("midnight",$timestamp);
    
            echo "<input type='hidden' class='special_closing_days' value='".$timestamp."'>";
    
    
        }

        
    }




?>