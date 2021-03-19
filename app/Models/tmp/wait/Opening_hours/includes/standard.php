<?php

    $last = "";
    $block = 0;


    foreach($openingHours as $day_number => $val){


        if($val["closed"]){
			
            $to = $from = "closed";
            
        
        } else {
            
            $from = $val["from"];
            $to   = $val["to"];
            
        }
    
        if($last != $from.$to ){
                                            
            $block++;
            $last = $from.$to;
            
        }
        
        $days[1] = Sentence::translate("monday");
        $days[2] = Sentence::translate("thursday");
        $days[3] = Sentence::translate("wednesday");
        $days[4] = Sentence::translate("thursday");
        $days[5] = Sentence::translate("friday");
        $days[6] = Sentence::translate("saturday");
        $days[7] = Sentence::translate("sunday");

                
        $opening[$block][] = array("day"=>$days[$day_number],
                                   "from"=>$from,
                                   "to"=>$to
                                   );	



    }

?>

  

<div class="opening_hours_wrapper">
    
    
    <div class="container-fluid">

        
        <?php
                
            foreach($opening as $arr){
                    

            $start_arr = current($arr);	
            $end_arr   = end($arr);				
                                
            $start_day = mb_substr($start_arr["day"],0,3,'utf-8');
            $end_day   = mb_substr($end_arr["day"],0,3,'utf-8');;
                    
            $from = $start_arr["from"];
            $to   = $start_arr["to"];

        ?>


            <div class="row">
                

                <div class="col">
                
                    <?php 
                            
                        if($start_day != $end_day){
                                
                            echo $start_day." - ". $end_day;
                                
                        } else {
                                
                            echo  $start_day;
                                    
                        }
                            
                    ?> 
                        
                </div>

                    
                <div class="col text-right nowrap">
                        
                    <?php 
                        
                        
                        $to = str_replace("00:00","24:00",$to);
                            
                        
                        if($from == "closed"){
                                
                            echo Sentence::translate("Closed");
                                    
                        } else {
                                
                            echo $from." - ".$to;
                            
                        }
                                
                            
                    ?>
                 

                </div>
        
            </div>


        <?php

            }
                
        ?>

    </div>


</div>

    

    <?php


        $closing_time = 
        strtotime("today ".$openingHours[date("N")]["to"]);

        $close_webshop = 
        Settings::get("close_webshop_after_closing_time");

        $minutes_before = 
        Settings::get("close_webshop_x_minutes_before_closing_time");


        if($close_webshop){


            echo "<div class='special_opening_hours'>";

            echo Sentence::translate("The webshop closes at");
                
            echo date(" H:i",($closing_time - ($minutes_before * 60)));

            echo "</div>";


        }

        
    ?>


 
 <?php
 
 /*
 ?>
    
    <?php
        
        $lukning_af_webshop = indstolinger("lukning_af_webshop");
        
        if($lukning_af_webshop != 0){
            
        $lukning_tid = date("H:i",($lukket_i_day - ($lukning_af_webshop * 60)));
        
    ?>
    
        <div class="lukker_for_bestolinger">
            
            Lukker for bestolinger kl. <?php echo $lukning_tid;?>
            
        </div>
        

    <?php
        }
    ?>

</div>

<div class="lukke_info">
<?php //echo "<br>".$string; ?>
</div>
<?php
*/
?>

