<div id="obj-<?php echo $object_ref_id;?>" class="post_wrapper">
					

    <?php

        $col = [];

            
        foreach($result as $val){

                            
            $title          = $val->title; 

            $description    = $val->description;

            $button_text    = $val->button_text;

            $image_ref_id   = $val->image_ref_id;

            $new_window     = $val->new_window;

            $image_cover    = $val->image_cover;

            $public_date    = $val->public_date;


            if($image_cover){ $image_class = "cover"; } else { $image_class = "contain"; }

           

            $link = "";

            if(!empty($val->link)){

                $url = Languages::lang_url();

                $link = $url . $val->link;

            }

                
            $image = "";


            ob_start();

    ?>


            <div class="post_content">

                
                <?php

                    if($link){

                        echo "<a href='".$link."'";
                        
                        if($new_window){ echo " target='_blank' "; }

                        echo ">";

                    }
                

                    if($image_ref_id){

                        $image =  null;
                       // Cloudinary::get($image_ref_id,800,false);
                     
                    }
                    

                    $imgclass = [];
              
                    
                    if($size){  $imgclass []= $size; }
                
                    if($top){   $imgclass []= $top; }

                    if($bottom){  $imgclass []= $bottom; }



                    if(!empty($image)){

                        echo '
                        <div class="post-image '.implode(" ",$imgclass).'"
                        style="background:url(\''.$image.'\')"></div>';

                    }
                    
                                              

                    if($link){

                        echo "</a>";

                    }


                ?>



                <div class="description">


                    <h3><?php echo Sentence::get($title); ?></h3>

                    <p><?php echo Sentence::get($description); ?></p>
                
                </div>



                <?php

                    if(!empty($button_text)){
                            
                            
                        $s = Sentence::get($button_text);
                            

                        if($s){

                            echo "<a href = '".$link."' "; 

                            if($new_window){ echo " target='_blank' "; }
                                
                            echo "class='information-button'>";

                            echo $s;

                            echo "</a>";

                        }
                            

                    }
                    
                ?>
  
            
            </div>

            
    <?php


            $content = ob_get_contents();

            ob_end_clean();

        
            $col[]= $content;


        }

        
        $options = $props;
        
        $options["p"] = 3;


        echo Col::insert("obj-".$object_ref_id,$col,$options);

            
    ?>


</div>
