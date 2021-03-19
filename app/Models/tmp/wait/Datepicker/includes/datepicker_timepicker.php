
    <div class="datepicker-content-wrapper">
        
    
        <label class="datepicker-field">


            <i class="far fa-calendar-alt"></i>


            <div class="label">
                
                <input type="text" 
                id="<?php echo $name;?>"
                name="<?php echo $name;?>[datepicker]" 
                value="<?php echo date("d-m-Y",$default_timestamp); ?>" 

                data-name='<?php echo $name;?>'
                data-type='<?php echo $type;?>'
                data-product_ref_id='<?php echo intval($product_ref_id);?>'
                data-persons='<?php echo intval($persons);?>'
                data-min_timestamp='<?php echo intval($min_timestamp);?>'
                data-onchange='<?php echo $onchange;?>'
                data-onload='<?php echo $onload;?>'
                data-time_type='<?php echo $time_type;?>'
                data-next_day_if_empty='<?php echo $next_day_if_empty;?>' 
                readonly
                class="datepicker">
                
            </div>

        </label>
        
  
        <?php 
           
        
            echo '<div class="timepicker-field">';



                if($timepicker){
           
               
                    $arr = [
                        "name" => $name,
                        "type" => $type,
                        "onchange" => $onchange,
                        "default_timestamp" => $default_timestamp,
                        "now"=>$now,
                        "min_timestamp" => $min_timestamp
                    ];
        
                    
                    echo Timepicker::insert($arr);

                }

            

            echo '</div>'; 
     

        ?>

        
        
    </div>

    
    <?php



        for($day = 1; $day <= 7; $day ++){

            echo '<div id="day_number_'.$day.'" class="day">';

            echo '</div>';
    
        }

    ?>

