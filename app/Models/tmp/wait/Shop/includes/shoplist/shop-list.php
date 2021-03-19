<?php


    echo "<div class='shop-list ".$layout."'>";
    
    
    if(isset($main_category_ref_id)){

         echo "<input type='hidden' id='shop_list_category' value='".$main_category_ref_id."' readonly>";


    }
   


    if($has_filter){

        include(__DIR__."/filter_menu/filter_menu.php");

    }



    echo "<ul class='shop-product-overview'>";

        
        $p = current($result);  

     
        if($p["parent_id"] != 0)
        if(!isset($pre_arr)){

            $parent_id = $p["parent_id"];

            $pre_arr = Categories::get( ["id"=>[$parent_id], "no_products" => 1] );
            

            if(current($pre_arr)["image_ref_id"] != 0){

                $result = array_merge($pre_arr,$result);

            }
            
           // sa($pre_arr);
        }
   
//sa($result);

        $price["min"] = 0;
        $price["max"] = 0;

        
        if(isset($result))
        foreach($result as $arr2){
            

            $category_ref_id = $arr2["id"];

            
            if(isset($arr2["min_price"]))
            if($price["min"] > $arr2["min_price"] or !$price["min"]){ $price["min"] = $arr2["min_price"]; }


            if(isset($arr2["max_price"]))
            if($price["max"] < $arr2["max_price"] or !$price["max"]){ $price["max"] = $arr2["max_price"]; }



            $no_products = true;

            
            $name = $arr2["name"];
            
            $description = $arr2["description"];
            
            $image_ref_id = $arr2["image_ref_id"];

            $total_products = $arr2["total_products"];

            $hide_if_unavariable = true;

  
        
            // groupe discount

         
            if($in_groupes){


                echo "<div class='product_wrapper product_wrapper_".$category_ref_id."'>";


                    include(__DIR__."/categories.php");


                echo "<div class='product_groupe'>";

                
                if(!$is_search){
                    
                    include(__DIR__."/category_overview.php");
                
                }
            

            }



            $ignore_product = false;

            // variable period

            if($category_ref_id)
            if($hide_if_unavariable)
            if(!Categories::available($category_ref_id)){

                $ignore_product = true;

            }


            if(empty($arr2["products"])){
                
                $ignore_product = true;

            }
            
         
            // if product not should be ignored
            
            if(!$ignore_product){

                $products = $arr2["products"];

                

                $no_products = false;
                
                $p = [
                    "products"=>$products,
                    "has_product_prevew"=>$has_product_prevew,
                    "has_amount"=>$has_amount,
                    "custom_cart_function"=>$custom_cart_function
                ];
          
                self::render_products($p);

            }
            

        

     
            if($in_groupes){

                echo "</div>";
                
                echo "</div>";

            }
            

        }  

        
   
 

        

        echo "<input type='hidden' id='min_price' value='".$price["min"]."' readonly>";

        echo "<input type='hidden' id='max_price' value='".$price["max"]."' readonly>";



        if($no_products){ 
            

            $search_word = Search::get_search_word();

            if($search_word){

                $txt = Sentence::translate("Sorry, no results were found for");
                
                $txt .= " <span class='search-word'>'".$search_word."'</span>";

            } else {

                $txt = Sentence::translate("There are no products in this category");

            }
            


            echo "<input type='hidden' id='shop_empty_products' readonly>";

            echo "<div class='alert alert-info m-3 no_products'>".$txt."</div>";
            
        }
        

        
        if(isset($category_ref_id)){

            include(__DIR__."/pager.php");

        }
        



    echo "</div>";


    echo "</ul>";

?>