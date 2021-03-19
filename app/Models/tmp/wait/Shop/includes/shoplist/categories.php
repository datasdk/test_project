
    <?php


        
        if($has_banner){

            
            $bg = "";

            $class = "";

            $image = false;
            

            if($image_ref_id){
        

                $image = Cloudi::get($image_ref_id,1000);      
                
                $class = "has_image";

                $bg = 'style = "background-image:url(\''.$image.'\')"';


            }
            

            //$class = "has_image";
  

                
                echo "<div class='category_wrapper'>";

                
                    echo "<div class='category_header ".$class."'>";

                        if($image_ref_id)
                        echo "<img src='".$image."'  alt='".$_SERVER["HTTP_HOST"]."'>";

                    

                        echo "<div class='text_wrapper category_text_".$category_ref_id."'>";

                            if(!empty($name))
                            echo "<h4>".ucfirst($name)."</h4>";

                            if(!empty($description))
                            echo "<p class='description'>".nl2br($description)."</p>";

                        echo "</div>";



                    echo "</div>";

                    
                echo "</div>";


                


            
        }
        


    ?>

