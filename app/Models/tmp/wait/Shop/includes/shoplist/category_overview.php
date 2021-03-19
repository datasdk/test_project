<?php


    $children = Categories::get_childrens($category_ref_id);



    if(!empty($children)){


        echo '<div class="category_overview">';
            

        foreach($children as $child_category_ref_id){


            $arr = current(Categories::get(["categories" => $child_category_ref_id]));


            $url = $arr["url"];

            $name = $arr["name"];

            $image_ref_id = $arr["image_ref_id"];


            $class = "";


            echo "<a href='".$url."'";  


                if($image_ref_id)
                if(isset($images[$image_ref_id])){


                    $bg = Cloudi::get($image_ref_id);


                    if($bg){

                        echo "style='background-image:url(".$bg.")'";

                    }        

                    $class = "has_backround_image";

                }
                

            echo ">";
                    
                    

            echo "<h1 class='".$class."'>";
                    
        
                echo ucfirst($name);

        

            echo "</h1>";

            echo "<div class='overlay'></div>";

            echo "</a>";


        }

    
        echo '</div>';


    }

 

?>


