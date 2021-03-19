<?php

    $days[1] = Sentence::translate("monday");
    $days[2] = Sentence::translate("thursday");
    $days[3] = Sentence::translate("wednesday");
    $days[4] = Sentence::translate("thursday");
    $days[5] = Sentence::translate("friday");
    $days[6] = Sentence::translate("saturday");
    $days[7] = Sentence::translate("sunday");


    $block = 0;
    $last = 0;


    foreach($obj as $day_number => $val){


        $day = $days[$day_number];

        $from = $val["from"];
        $to = $val["to"];
        $active = $val["active"];


        if(!$active){
			
            $to = $from = "Lukket";
            
        
        } 

    
        if($last != $from.$to ){
                                            
            $block++;
            $last = $from.$to;
            
        }
        
                
        $interval[$block][] = array("day"=>$days[$day_number],
                                    "from"=>$from,
                                    "to"=>$to
                                    );	


    }

    
?>





    <?php
        
        foreach($interval as $arr){
            
            $start_arr = current($arr);	
            $end_arr   = end($arr);				

            $start_day = mb_substr($start_arr["day"],0,3,'utf-8');
            $end_day   = mb_substr($end_arr["day"],0,3,'utf-8');
            
            $from = $start_arr["from"];
            $to   = $start_arr["to"];

    ?>

    
    <div class="interval-wrapper">
        
        <div class="date">
        
            <?php 
                    
                if($start_day != $end_day){
                        
                    echo $start_day." - ". $end_day;
                        
                } else {
                        
                    echo  $start_day;
                            
                }
                    
             ?> :
                
        </div>
            
        <div class="time">
                    
            <?php 
                    
                $to = str_replace("00:00","24:00",$to);
                        
                if($from == "Lukket"){
                            
                    echo "Lukket";
                                
                } else {
                            
                    echo $from." - ".$to;
                        
                }
                        
                        
            ?>
                    
        </div>

    </div>

        
    <?php

        }
        
    ?>

