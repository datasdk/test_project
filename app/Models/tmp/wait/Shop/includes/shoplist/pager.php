<?php


    //$products = Categories::get_product_amount($categories);


    if($total_products <= 100){ return false; }


   $current_page = $page;


    $pages = ceil($total_products / 100);

    $min = $current_page - 3;

    $max = $current_page + 3;


    $prev = ($current_page - 1);

    $next = ($current_page + 1);


    if($min <= 0){ 

        $min = 0;

        $max = 6;

    }


    if($pages > 6)
    if($max > $pages){

        $min = $pages - 6;

        $max = $pages;

    }

    

    echo "<div class='shop_pager'>";

        
        if($current_page > 3)
        echo "<a href='javascript: open_shoplist(".$main_category_ref_id.",0)'><i class='fas fa-angle-double-left'></i>";


        if($current_page > 0)
        echo "<a href='javascript: open_shoplist(".$main_category_ref_id.",".$prev.")'><i class='fas fa-angle-left'></i>";


        for($i=0; $i<$pages; $i++){


            if($i < $min or $i > $max){ continue; }

            $class = "";

            if($i == $current_page){ $class = "hightlight"; }

            echo "<a href='javascript: open_shoplist(".$main_category_ref_id.",".$i.")' class='".$class."'>";
            
            echo ($i + 1);
            
            echo "</a>";


        }


        if($next < $pages){

            echo "<a href='javascript: open_shoplist(".$main_category_ref_id.",".$next.")'><i class='fas fa-angle-right'></i>";
        
        }


        if($current_page < $pages - 4){

            echo "<a href='javascript: open_shoplist(".$main_category_ref_id.",".($pages - 1).")'><i class='fas fa-angle-double-right'></i>";
     
        }
        

    echo "</div>";




?>