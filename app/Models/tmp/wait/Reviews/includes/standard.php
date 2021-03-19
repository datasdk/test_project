<div class="review_wrapper">


    <div class="review_content">


        <ul class="slider">


            <?php

                foreach($review[$object_ref_id] as $id => $val):


                    $max_stars = 5;

                    $stars = $val["stars"];

                    $no_stars = $max_stars - $stars;
                    
                    $content = $val["content"];

                    $image = $val["image"];

                    $customer = $val["customer"];
                    
                    $only_image = false;


                    if(empty($content) AND empty($stars)){

                        $only_image = true;

                    }


                    $class = "class='img-round'";

                    
                    if($only_image){

                        $class = "";

                    }
            
            ?>


                <li>


                    <div class="review">

                        <?php

                            if($image):
                        
                        ?>

                            <?php if(!$only_image){ echo "<div class='img'>"; } ?>
                            

                                <img src="<?php echo $image; ?>" <?php echo $class; ?>  alt='<?php echo $_SERVER["HTTP_HOST"];?>'>

                            
                            <?php if(!$only_image){ echo "</div>"; } ?>


                        <?php

                            endif;

                        ?>

                        <?php

                            if(!$only_image):

                        ?>

                            <div class="quotes">
                                

                                <?php

                                    if($stars):

                                ?>


                                    <div class="stars">

                                        <?php
                                            
                            
                                            echo str_repeat("<i class='fas fa-star'></i>",$stars);

                                            echo str_repeat("<i class='fas fa-star no_stars'></i>",$no_stars);


                                        ?>

                                    </div>

                                <?php

                                    endif;
                                
                                ?>


                                <?php

                                    if($content):

                                ?>

                                    <div class="review-quote">

                                        <?php echo $content; ?>

                                    </div>

                                <?php

                                    endif;
                                
                                ?>


                                <?php

                                    if($customer):

                                ?>

                                    <span><?php echo $customer; ?></span>
                                
                                <?php
                                
                                    endif;

                                ?>

                            </div>


                        <?php

                            endif;

                        ?>
                    
                    </div>


                </li>


            <?php

                endforeach;
            
            ?>


        </ul>


    </div>
    

</div>






