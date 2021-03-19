<div id="stars-<?php echo $media_ref_id?>" class="stars-<?php echo $media_ref_id?> stars rating-wrapper">

    <?php
        
  
        $r = Media_interaktion::get($media_ref_id, $customer_ref_id);

  

        $stars = $r["stars"];
       

                                
        for($i=1; $i<=5; $i++){


            $class = "";

            if($i <= $stars){ $class = "active"; }
                                    

            echo '
            <a 
            class="star_'.$i.' star '.$class.'" 
            data-media_ref_id="'.$media_ref_id.'"
            data-customer_ref_id="'.$customer_ref_id.'"
            data-stars="'.$i.'">
            <i class="fas fa-star"></i>
            </a>';

        }
                            
    ?>


</div>