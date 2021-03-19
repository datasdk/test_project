

<select 
name="<?php echo $name; ?>[timepicker]" 
class="timepicker"
data-skip_to_next_day="<?php echo intval($skip_to_next_day); ?>"
data-onchange="<?php echo $onchange;?>"
>

    <?php



        foreach( $time_values as $timestamp => $clock){

            
            $sel = "";

            if($default_timestamp == $timestamp){ $sel = " selected "; }

            echo '<option value="'.$clock.'" '.$sel.'>'.$clock.'</option>';

        }
    
    ?>
    

</select>