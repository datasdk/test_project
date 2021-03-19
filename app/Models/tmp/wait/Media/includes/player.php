<div id="sound_<?php echo $media_ref_id; ?>" class="sound_wrapper sound_<?php echo $media_ref_id; ?>">


    <div class="header">


        <div class="title">

            <h1><?php echo ucfirst($name); ?></h1>  

            <span class="date">

                <?php 
                                
                    echo date("d/m Y",$from_time); 
                        
                ?>

            </span>

        </div>


        <audio class="plyr_player" controls>
        <source src="<?php echo $url ; ?>" type="audio/mp3" />
        </audio>
        
    </div>



    <?php 

        if($customer_ref_id){ 


            echo '<div class="sound-bottom-bar">';


            $key = sha1("customer_".$customer_ref_id."_sound_".$media_ref_id);
            
      
            if($has_share){

        ?>


            <div class="share">

                <a href="javascript:share_sound('<?php echo $baseurl; ?>/share/<?php echo $media_ref_id; ?>/<?php echo $key; ?>')" >
                <i class="fas fa-share-alt"></i>
                </a>

            </div>


        <?php 

            }

           
    
            if($has_rating){ 

                Media::rating($media_ref_id, $customer_ref_id);
        
            } 
            

            echo "</div>";

        } 
    
    ?>


</div>

