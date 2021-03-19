<?php

    $category_ref_id = Categories::getCategoryByUrl();
    


    $search = Search::get_product_id_by_search();


    $result = Shop::load_inventory(["category_ref_id" => $category_ref_id, "search" => $search]);



    if($category_ref_id){

         if(isset($result["filter"][$category_ref_id])){
        
            $variants = $result["filter"][$category_ref_id];

        }

    } else {

        if(isset($result["filter"])){
     
            $variants = current($result["filter"]);

        }

    }
   


   
?>


<?php

    if($has_filter)
    if(!empty($variants)):

?>

    <div class="filter noselect">

        
        <a class="filter-btn">Filtrer og sorter<i class="fas fa-sliders-h"></i></a>


        <div class="filter-content">
                

            <?php

                foreach($variants as $variant_category_ref_id => $arr){


                    echo '<div class="category">';
                    
                    echo '<div>';
                    
                        echo '<strong>'.ucfirst(Sentence::get($arr["variant_category_name"])).'</strong>';

                        echo '<a href="javascript:" class="reset float-right">'.Sentence::translate("Reset").'</a>';
                    
                    echo "</div>";
                    

                    $i = 0;


                    $arr["variants"] = 
                    Format::sort($arr["variants"]);


                    foreach($arr["variants"] as $variant_ref_id => $arr2){


                        $i++;

                        echo '<label class="variant">';
                            
                        echo '<input type="checkbox" class="filter_field" value="'.$variant_ref_id.'">';

                        echo ucfirst(Sentence::get($arr2["variant_name"]));
                            
                        echo '</label>';



                    }
                
                    

                    if($i > 10){

                        echo '<a href="javascript:" class="show_more">'.Sentence::translate("Show more").'</a>';

                    }
                    


                    echo '</div>';;

                }

            ?>


            <div class="filter-btn-wrapper">
                
                <button class="filter-update mb1"><?php echo Sentence::translate("Update list");?></button>
                
                <button class="filter-clear"><?php echo Sentence::translate("Clear list");?></button>

            </div>


        </div>

    </div>


<?php
        
    endif;

?>