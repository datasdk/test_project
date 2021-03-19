<?php

    $categories = Categories::get_all_categories();
    
    if(empty($categories)){

        return false;

    }

?>


<div class="category_list_wrapper noselect">


    <?php

        $title = $root_category;

        $category_ref_id = Categories::getCategoryByUrl();
        
        if($root_category === "*"){ $title = "Kategorier"; }


        echo '<div class="headline">Kategorier</div>';


            echo '<div class="section_list_wrapper">';

                    
                 echo '<ul> ';  

                    
                    $parent_id = 0;
              
                    $tree = Tree::build($categories,0);

                    $shopmenu_parents = array();
                    
        
               
                    Tree::expand($tree ,function($val,$params){


                        
                        $id = $val["id"];

                        $name = $val["name"];

                        $image_ref_id = $val["image_ref_id"];

                        $url = $val["url"];

                        $category_ref_id = $params["category_ref_id"];

                   

                        $class = "";

                        if($id == $category_ref_id){

                            $class = "highlight";

                        }



                        echo '<li class="'.$class.'">';

                            echo '<div class="item">';

                                echo '<a href="'.$url.'">'.ucfirst($name).'</a>';

                            echo '</div>';

                        echo '</li>';

                    

                    },function($val,$params){



                        $id = $val["id"];

                        $url = $val["url"];

                        $name = $val["name"];

                        $image_ref_id = $val["image_ref_id"];
                        
                        $category_ref_id = $params["category_ref_id"];
                        
    

                        $class = "";

                        if($id == $category_ref_id){

                            $class = "highlight";

                        }



                        echo '<li>';
                        

                        echo '<div class="'.$class.'">';


                            echo '<div class="item">';


                                echo '<a href="'.$url.'">';
                                
                                    echo ucfirst($name);

                                echo '</a>';
                            

                                echo '<i class="fas fa-angle-down"></i>';
                         

                            echo '</div>';

                            

                        echo '</div>';




                        echo '<ul class="child">';



                    },function(){
                        
           

                        echo '</li>';

                        echo "</ul>";

                    },
                    array("category_ref_id"=>$category_ref_id)
                );


                echo '</ul>';

            echo '</div>';
             

    ?>

</div>