<?php
                    
                    $main_image = array();

                    $thumb_image = array();

                    
                    echo "<div class='image-wrapper'>";

      
                        if(!empty($val["images"]))
                        foreach($val["images"] as  $image_ref_id){
                                
                            if($image_ref_id){
                                    
                                $main_image[$image_ref_id]  = Cloudi::get($image_ref_id,1400,false,80);
                                $thumb_image[$image_ref_id] = Cloudi::get($image_ref_id,40,40);

                            }

                        }



                        if(empty($main_image)){

                            $main_image[] = "/assets/images/interface/no_image.jpg";

                        }

                        
                        foreach($main_image as $image_ref_id => $url){

                            echo "
                            <div class='image main_img_".$image_ref_id." main_img zoom_wrapper' 
                            data-image = '".$url."' >";
                        
                            echo "<img src='".$url."'  alt='".$_SERVER["HTTP_HOST"]."'>"; 
        
                            echo "</div>";

                        }
                        
                        



                        if(count($thumb_image) > 1){

                            
                            echo '<div class="thumb-wrapper">';
                            

                            foreach($thumb_image as $image_ref_id => $url){

                    
                                $variantARR = array();
                                
                                /*
                                
                                    if(isset($arr1["variant_ref_id"]))
                                    foreach($arr1["variant_ref_id"] as $variant_ref_id){

                                        $variantARR[]= "variant_".$variant_ref_id;

                                    }

                                */
                                    
                                    
        
                                echo "
                                <span
                                class='thumbnail_".$image_ref_id." thumbnail ".implode(" ",$variantARR)."'
                                data-image-ref-id='".$image_ref_id."'>";
        
                                echo "<img src= '".$url."'  alt='".$_SERVER["HTTP_HOST"]."'>";         
                                        
                                echo "</span>";
                                

                            }
                        
                            echo "</div>";

                        }

                    
                    echo "</div>";

?>